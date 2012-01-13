<?php
App::import('Vendor', 'Markdownify');
class ImportOldDatabaseShell extends Shell {
	var $uses = array("Group","User","Event","Course","Series","Role","Result","Organizer");
	var $timezone;
	// Club ID of GVOC
	var $clubId = 1;
	
	function main() 
	{
	  /*
	  for($i = 0; $i < 50; $i++) {
		 echo "$i translates to: ".$this->translateUserId($i)."\n";
	  } */
	  // Ted de St. Croix: 38-->4, 312-->4

		// Set timezone for time imports.. This will mess things up for stuff out of PST, but that shouldn't be much..
		$this->timezone = new DateTimeZone("America/Vancouver");
		// Need some more RAM..
		ini_set('memory_limit', '160M');
		
		$this->debugUsers();
		/*
$this->importUsers();
		//$this->importSeries();
		$this->importEvents();
		$this->importCourses();
		//$this->importRoles();
		$this->importResults();
		$this->importOrganizers();
*/
	}
	
	function debugUsers() {
	   $users = $this->User->query('SELECT * FROM users');
	   foreach($users as $user) {
	       echo $user['users']['usr_name']." =>".$this->decodeOldPassword($user['users']['usr_PW'])."\n";
	   }
	}
	
	function importUsers() 
	{
	  $this->User->query("DELETE FROM wjr_users");
		// Do a generic query to get data from old database tables. It really should just use a generic Model, rather than the User model, but I couldn't get that to work in the shell
		$oldUsers = $this->User->query("SELECT * FROM users");

		foreach($oldUsers as $oldUser) {
		 $oldUser = $oldUser["users"];
		 $oldId = intval($oldUser['usr_ID']);
		 $newId = $this->translateUserId($oldId);
		 if($newId== NULL) {
			//echo "deleting = " . $oldUser['usr_ID']."\n";
			// Don't do anything. I.e. account gets removed
		 }
		 else {
			$this->User->create();
			$user = array();
			$user["id"] = $newId;
			
			$user["name"] = ucwords($oldUser['usr_fullname']);
			if($oldUser['usr_name'] != "nonuser") {
			   $user["username"] = $oldUser['usr_name'];
			}
			if(!empty($oldUser['usr_email'])) {
			   $user["email"] = str_replace(" ","",$oldUser['usr_email']);
			}
			if(!empty($oldUser['sinumber'])) {
			   $user['si_number']  = $oldUser['sinumber'];
			}
			if(!empty($oldUser['usr_HowHear'])) {
			   $user['referred_from']  = $oldUser['usr_HowHear'];
			}
			if(!empty($oldUser['usr_PW'])) {
			   $user["password"] = $this->decodeOldPassword($oldUser['usr_PW']);
			}
			
			switch($oldUser['usr_LVL']) {
			   case 99: 
				  $user["group_id"] = 5;
				  break;
			   case 80: 
				  $user["group_id"] = 4;
				  break;
			   case 33: 
				  $user["group_id"] = 3;
				  break;
			   case 22: 
				  $user["group_id"] = 2;
				  break;
			   case 11: 
				  $user["group_id"] = 1;
				  break;
			}
			// For admin users, set group_id = 6
			if($oldUser["usr_ID"] == 1 || $oldUser["usr_ID"] == 5 || $oldUser["usr_ID"] == 911) {
			   $user["group_id"] = 6;
			}
			
			$data["User"] = $user;
			$data = $this->User->hashPasswords($data);
			if(!$this->User->save($data, false)) {
			   print_r($data["User"]);
			   die("User import failed.. See the data above to see what is causing the data to fail validation.");
			}
		 }
		}
	}
	
	/**
	* Skipping importing:
	* cal_isownevent (all 1)
	* cal_grade (almost all 11)
	* cal_isImportant (a few recent events using this, but probably should just be re-implemented later if necessary)
	* cal_isClubEvent (all 1)
	* cal_Link (almost all null)
	* cal_Details (almost all null)
	* cal_Location (only very old events)
	* cal_Date (duplicitous)
	* cal_Time (duplicitous)
	* cal_Organizer (hardly any..)
	* cal_Locality (no, events should be tied to a club and a lat lon point)
	* cal_isResultsPosted (NOT SURE whether this should be imported)
	* cal_isEntriesOpen (not being used properly)
	* cal_isEventFinished (not being used properly)
	* cal_Timestamp (not being used properly)
	**/
	function importEvents() 
	{
	  $this->Event->query("DELETE FROM events");
		$oldEvents = $this->User->query("SELECT * FROM cal_data");
		$md = new Markdownify;
		foreach($oldEvents as $oldEvent) {
			$this->Event->create();
			$oldEvent = $oldEvent["cal_data"];
			
			$event = array();
			$event["id"] = $oldEvent['cal_ID'];
			$event["name"] = $oldEvent["cal_Title"];
			$timeString = "YY- MM-DDTHH:II:SS-8:00";
			// I dunno, manipulating objects in PHP seems awkward compared to java..
			// Take time from old database, store as a PHP/MySQL DateTime
			// FIXME-RWP Not working :(
			$event["date"] = new DateTime();
			$event["date"]->setTimezone($this->timezone);
			$event["date"]->setDate($oldEvent["cal_Year"], $oldEvent["cal_Month"], $oldEvent["cal_Day"]);
			$event["date"]->setTime($oldEvent["cal_Hour"], $oldEvent["cal_Minute"]);
			
			// Convert back to text for saving with CakePHP (give it UTC)
			$event["date"]->setTimezone(new DateTimeZone("UTC"));
			$event["date"] = $event["date"]->format(DATE_ATOM);
			//die($event["date"]);
			
		 	$event["description"] = "";
		 	$md = new Markdownify;
			if(!empty($oldEvent["cal_Text"]))
				$event["description"] = $this->sanitizeHtml($oldEvent["cal_Text"]);			
						
			if(!empty($oldEvent["cal_EventReport"])) {
				$event["description"] .= "<h3>Event Report</h3>";
				$event["description"] .= $this->sanitizeHtml($oldEvent["cal_EventReport"]);
			}

			$event["description"] = $md->parseString($event["description"]);
			
			$event["results_posted"] = $oldEvent["cal_isResultsPosted"];
			$event["is_ranked"] = $oldEvent["cal_isRanked"];

			$event["club_id"] = $this->clubId;
			// Allow content managers and up to access/edit? In addition to organizers I think
			$event["group_id"] = 5;
			
			
			switch($oldEvent["cal_Category"]) {
				case 26: $event["series_id"] = 1;
				break;
				case 25: $event["series_id"] = 2;
				break;
				default: $event["series_id"] = NULL;
				break;
			}
			
			$data["Event"] = $event;
			if(!$this->Event->save($data)) {
				print_r($data["Event"]);
				die("Event import failed.. See the data above to see what is causing the data to fail validation.");
			}
		}
	}
	
	function importCourses() 
	{
	  $this->Course->query("DELETE FROM courses");
		$oldCourses = $this->User->query("SELECT * FROM evt_courses");

		foreach($oldCourses as $oldCourse) {
			$this->Course->create();
			$oldCourse = $oldCourse["evt_courses"];
			
			$course = array();
			$course["id"] = $oldCourse["uid"];
			$course["event_id"] = $oldCourse["eventid"];
			
			if(!empty($oldCourse["description"])) {
				$course["name"] = $oldCourse["description"];
			}
			
			// Conver to meters
			if($oldCourse["courselen"] != 0) 
				$course["distance"] = $oldCourse["courselen"]*1000;
			
			if($oldCourse["courseclimb"] != 0)
				$course["climb"] = $oldCourse["courseclimb"];

			if(!empty($oldCourse["notes"]))
				$course["description"] = $oldCourse["notes"];
						
			$data["Course"] = $course;
			if(!$this->Course->save($data)) {
				print_r($data["Course"]);
				die("Course import failed.. See the data above to see what is causing the data to fail validation.");
			}
		}
	}
	
	function importRoles() 
	{
	  $this->Role->query("DELETE FROM roles");
		$oldRoles = $this->User->query("SELECT * FROM evt_orgtype");

		foreach($oldRoles as $oldRole) {
			$this->Role->create();
			$oldRole = $oldRole["evt_orgtype"];
			
			$role = array();
			$role["id"] = $oldRole["evt_org_ID"];
			
			// Move Standard to id=4
			if($role["id"] == 0)
				$role["id"] = 4;
				
			$role["name"] = $oldRole["Short"];
			if($oldRole["Description"])
				$role["description"] = $oldRole["Description"];
					
			$data["Role"] = $role;
			if(!$this->Role->save($data)) {
				print_r($data["Role"]);
				die("Role import failed.. See the data above to see what is causing the data to fail validation.");
			}
		}
	}
	
	/*
	Skipped:
	eventid (redundant)
	coursecode (redundant)
	isranked (redundant with course is_ranked)
	timeh, timem, times (redundant with timesec)
	relpts (rendundant with rankpts?)
	*/
	function importResults() 
	{
	  $this->Result->query("DELETE FROM results");
		//$oldResults = $this->User->query("SELECT * FROM evt_runners WHERE (userid=4 OR userid=38 OR userid=301) AND courseid=20");
		$oldResults = $this->User->query("SELECT * FROM evt_runners");

		foreach($oldResults as $oldResult) {
			$this->Result->create();
			$oldResult = $oldResult["evt_runners"];
			
			$result = array();
		 $userId = $oldResult["userid"];
		 $resultId = $oldResult["uid"];
		 $newId = $this->translateUserId($userId);
		 $eventId = $oldResult["eventid"];

		 $courseId = $oldResult["courseid"];
		 $time = $oldResult["timesec"];
		 //echo "$userId --> $newId (course $courseId)\n";
			
		 // This is a strange situation, but here we have a result for a user that
		 // doesn't exist
		 if($newId == 0) {
			echo "Warning: Deleting result not belonging to anyone ($eventId,$courseId,$userId)\n";
			continue;
		 }
		 // When the user of this result is merged to another user we have to make sure
		 // that we do not overwrite the result of the user it is being translated to
		 // I checked on 2011-08-14 and this shouldn't really be a problem
		 if($newId != $userId) {
			// Check that we don't already have a result in our new database
			// for the translated person on this course
			$query = $this->Result->query("SELECT * FROM results
										 WHERE user_id = $newId AND
											   course_id = $courseId");
			if(count($query) > 0) {
			   // We don't want userId's result to overwrite newId's result
			   // Therefore skip processing this result
			   //echo "Result deleted -- We already have a result for $newId ($courseId,$userId)\n";
			   if($time > 0)
				  echo "Warning: Deleting a result with non-zero time ($resultId, $time)\n";
			   continue;
			}
			// Check that we don't have a result for this person left to add from the 
			// old database
			$query = $this->User->query("SELECT * FROM evt_runners
										 WHERE userid = $newId AND
										 courseid = $courseId");
			if(count($query) > 0) {
			   // We don't want userId's result to overwrite newId's result
			   // Therefore skip processing this result
			   //echo "Result deleted -- We will insert a result for $newId in the future ($courseId,$userId)\n";
			   if($time > 0)
				  echo "Warning: Deleting a result with non-zero time ($resultId, $time)\n";
			   continue;
			}
		 }
		 //echo "Inserting result for $newId\n";

			$result["id"] = $oldResult["uid"];
			$result["course_id"] = $oldResult["courseid"];
			$result["user_id"] = $this->translateUserId($oldResult["userid"]);
			
			if(!empty($oldResult["timesec"]))
				$result["time"] = $this->secondsToHms($oldResult["timesec"]);
				
			if(!empty($oldResult["rankpts"]))
				$result["points"] = $oldResult["rankpts"];
				
			$result["needs_ride"] = $oldResult["isneedride"];
			$result["offering_ride"] = $oldResult["isoffercarpool"];
			$result["non_competitive"] = $oldResult["isnoncomp"];
			
			$data["Result"] = $result;
			if(!$this->Result->save($data)) {
				print_r($this->Result->invalidFields());
				print_r($data["Result"]);
				die("Result import failed.. See the data above to see what is causing the data to fail validation.");
			}
		}
	}
	
	/**
	* Hardcoded import
	* Just WJR (1)/WET (2)/DET (3)
	**/
	function importSeries() 
	{
	  $this->Series->query("DELETE FROM series");
		$series = array();
		array_push($series,array("club_id"=>1,"id"=>1,"acronym"=>"WJR","name"=>"Why Just Run","description"=>"Monthly orienteering race","club_id"=>1,"color"=>"#45892B", "information"=>"<b>What should I bring?</b> Wear something comfortable that you don't mind getting a little dirty! If it's wet outside, you might want to consider a change of shoes and socks! Long pants can be advantageous in forested parks on the more advanced courses. A compass is optional (we have extra ones for borrowing).<br><b>How much does it cost?</b> Adults: $10. Under 20 years of age: $5, Family maxiumum: $20. Membership required: Adult: ($5 for newcomers, $10 otherwise). Under 20: $5."));
		array_push($series,array("club_id"=>1,"id"=>2,"acronym"=>"WET","name"=>"Wednesday Evening Training","description"=>"Weekly evening training orienteering series","club_id"=>1,"color"=>"#583725", "information"=>"<b>What should I bring?</b> Wear something comfortable that you don't mind getting a little dirty! If it's wet outside, you might want to consider a change of shoes and socks! A headlamp or flashlight is critical in the winter months (late September to April). A compass is optional (we have extra ones for borrowing).<br><b>When should I sign up?</b>Please sign up by Tuesday night so that we can print enough maps, although we have extra maps if you forget.<br><b>How much does it cost?</b> All WETs are free with yearly membership ($5 for newcomers and those under 21, $10 otherwise, $20 maximum per family)<br><b>Post-training social:</b> After training we often gather at a nearby restaurant for post-training socializing. Everyone welcome!"));
		array_push($series,array("club_id"=>1,"id"=>3,"acronym"=>"DET","name"=>"Determinator","description"=>"","club_id"=>1));
	
		foreach($series as $singleSeries) {
			$this->Series->create();
			
			$data["Series"] = $singleSeries;
			if(!$this->Series->save($data)) {
				print_r($data["Series"]);
				die("Series import failed.. See the data above to see what is causing the data to fail validation.");
			}
		}
	}
	
	function importOrganizers() 
	{
	  $this->Organizer->query("DELETE FROM organizers");
		$oldOrganizers = $this->User->query("SELECT * FROM evt_usr");

		foreach($oldOrganizers as $oldOrganizer) {
			$this->Organizer->create();
			$oldOrganizer = $oldOrganizer["evt_usr"];

			 $userId = $oldOrganizer["eu_User"];
			 $newId = $this->translateUserId($userId);
			 $eventId = $oldOrganizer["eu_Event"];
			 $roleId = $oldOrganizer["orgtype"];

			 // This is a strange situation, but here we have an organizer for a user that
			 // doesn't exist
			 if($newId == 0) {
				echo "Warning: Deleting organizer not belonging to anyone ($eventId,$userId)\n";
				continue;
			 }

			 if($newId != $userId) {
				// Check that we don't already have an organizer for newId
				$query = $this->Organizer->query("SELECT * FROM organizers
											 WHERE user_id = $newId AND
											 event_id = $eventId AND
											 role_id = $roleId");
				if(count($query) > 0) {
				   //echo "Organizer deleted. Already exists\n";
				   continue;
				}
				// Check that we won't add this in the future
				$query = $this->User->query("SELECT * FROM evt_usr
											 WHERE eu_User = $newId AND
											 eu_Event = $eventId AND
											 orgtype = $roleId");
				if(count($query) > 0) {
				   //echo "Organizer deleted. Will exist ($newId, $eventId, $roleId)\n";
				   continue;
				}
			 }
			
			$organizer = array();
			$organizer["id"] = $oldOrganizer["eu_Key"];
			
			$organizer["event_id"] = $oldOrganizer["eu_Event"];
		 // The following should be safe. I.e. don't worry about overwriting
			$organizer["user_id"] = $newId;
			
			// Move role id=0 to id=4 (standard role)
			if($oldOrganizer["orgtype"] == 0) {
				$organizer["role_id"] = 4;
			} else {
				$organizer["role_id"] = $oldOrganizer["orgtype"];
			}
			
			$data["Organizer"] = $organizer;
			if(!$this->Organizer->save($data)) {
				print_r($this->Organizer->invalidFields());
				print_r($data["Organizer"]);
				die("Organizer import failed.. See the data above to see what is causing the data to fail validation.");
			}
		}
	}
	
	/**
	* Do some HTML cleanup.. Can be improved.
	*/
	private function sanitizeHtml($string) 
	{
		// The span replacement is non-optimal, but it seems to be better than leaving the span tags in
		$remove = array("<head>" => "","</head>" => "","<body>" => "","</body>" => "","<html>" => "","</html>" => "", "<span style=\"font-weight: bold;\">" => "<b>", "</span>" => "</b>");
		foreach($remove as $op => $replace) {
			$string = str_replace($op,$replace,$string);
		}
		return preg_replace('/[^(\x20-\x7F)]*/','', $string);
	}
	
	private function secondsToHms($seconds) 
	{
		$secs = $seconds % 60;
		$mins = (($seconds - $secs)/60)%60;
		$hrs = ($seconds - $secs - $mins*60) / 3600;
		
		return $hrs.":".$mins.":".$secs;
	}
	
	/**
	* Takes the old encrypted password to decrypt, returns the decrypted password
	*/
	private function decodeOldPassword($instr)
	{
		$transa = "0wXr5BSmaGNhfLIckQD7pVy2uZt3zUo8EPjdJKeiOF9nTA4sYv1xWq6CRlbHMg";
		$transb = strrev($transa);
		$instr = strrev($instr);
		$outstr = strtr($instr,$transa,$transb);
		return($outstr);
	}
   private function translateUserId($id) {
	  // Default to no translation
	  $newId = $id;
	  $this->User->id = $id;
	  $temp = $this->User->query("Select usr_fullname,usr_name FROM users WHERE usr_ID = $id");
	  if(!isset($temp[0])){
		 // User doesn't exist
		 return 0;
	  }
	  $name = $temp[0]["users"]["usr_fullname"];
	  $username = $temp[0]["users"]["usr_name"];

	  //////////////
	  // Deletion //
	  //////////////
	  // Check if results
	  $results = $this->User->query("SELECT count(userid) as C FROM evt_runners WHERE userid = $id");
	  $num[0] = $results[0][0]["C"] . "\n";
	  // Check if organizer
	  $results = $this->User->query("SELECT count(eu_User) as C FROM evt_usr where eu_User = $id");
	  $num[1] = $results[0][0]["C"] . "\n";
	  
	  if(max($num) == 0) {
		 // delete
		 //echo "Remove: $name, $username,$id\n";
		 return 0;
	  }

	  /////////////////
	  // Replacement //
	  /////////////////
	  // Check if there is another one
	  $results = $this->User->query("
		 SELECT
			usr_ID AS id, 
			(SELECT count(R.userid)  FROM evt_runners AS R WHERE R.userid = U.usr_ID GROUP BY R.userid ) AS C
		 FROM
			users AS U
		 WHERE 
			U.usr_fullname LIKE \"$name\"
		 ORDER BY C LIMIT 1 
		 ");
	  $count = $results[0]["0"]["C"];
	  //echo "$newId ... $count\n";
	  if($count == "") {
		 $newId = $id;
	  }
	  else {
		 $newId = $results[0]["U"]["id"];
	  }

	  return $newId;
   }
}
?>
