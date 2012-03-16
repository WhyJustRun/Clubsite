<?php
App::uses('Sanitize', 'Utility');

class Result extends AppModel {
	var $name = 'Result';
	var $displayField = 'time';
	var $actsAs = array('Containable');
	var $clubSpecific = false;
	var $validate = array(
		'id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'course_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'points' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'needs_ride' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'offering_ride' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		// IOF Standard participant statuses
		'status' => array(
			'status' => array(
				'rule' => array('inList', array('inactive', 'did_not_start', 'active', 'finished', 'ok', 'mis_punch', 'did_not_finish', 'disqualified', 'not_competing', 'sport_withdrawal', 'over_time', 'moved', 'moved_up', 'cancelled')),
				'required' => false, // Defaults to 'ok'
			),
		),
	);

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Course' => array(
			'className' => 'Course',
			'foreignKey' => 'course_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => false,
			'conditions' => array('Course.event_id = Event.id'),
			'fields' => '',
			'order' => ''
		)
	);

	function calculatePoints($course_id) {
		$meanTime   = $this->invMeanInvTimeByCourse($course_id);
		$meanPoints = $this->meanIncomingPoints($course_id);
		$results    = $this->findAllByCourseId($course_id);
		$isValidCourse  = $this->isValidCourse($course_id);
		$numValid   = $this->numValidRunners($course_id);
		foreach($results as &$result) {
			if($result["Result"]["time"] != "00:00:00" && $result["Result"]["time"] != NULL) {
				$currTime   = strtotime($result["Result"]["time"]) - strtotime("00:00:00");
				$currPoints = $meanPoints * $meanTime / $currTime;
				if($isValidCourse && $result["Result"]["status"] == 'ok') {
					$result["Result"]["points"] = $currPoints;
				}
				else{
					$result["Result"]["points"] = NULL;
				}
			}
			else{
				$result["Result"]["points"] = NULL;
			}
			$this->id = $result['Result']['id'];
			$this->saveField('points', $result["Result"]["points"]);
		}
	}
	function isValidCourse($course_id) {
		if($this->numValidRunners($course_id) < 3)
			return false;
		else
			return true;
	}
	// Compute inverse mean inverse time
	function invMeanInvTimeByCourse($course_id) {
		$conditions = array("course_id = " => $course_id);
		$data = $this->find("all", array("conditions" => $conditions));
		$meanTime = 0;
		$counter = 0;
		foreach ($data as $value=>$key) {
			$user_id = $key["Result"]["user_id"];
			if($key["Result"]["time"] != NULL && $key["Result"]["time"] != "00:00:00" && $key["Result"]["status"] == 'ok' && $this->isValidRunner($course_id, $user_id) == 1) {
				$hour = substr($key["Result"]["time"],0,2);
				$min  = substr($key["Result"]["time"],3,2);
				$sec  = substr($key["Result"]["time"], 6,2);
				$meanTime = $meanTime + 1./$this->toSec($key["Result"]["time"]);
				$counter += 1;
			}
		}
		if($counter == 0)
			return NULL;
		else
			return $meanTime = 1/($meanTime / $counter);
	}
	function isValidRunner($course_id, $user_id) {
		$courseDate = $this->Course->getDate($course_id);
		$lowerDate  = strtotime($courseDate) - 2 * 86400 * 365;
		$lowerDate  = date('Y-m-d h:i:s', $lowerDate);

		$conditions = array("user_id = " => $user_id, "status" => 'ok', "time > "=>0, "Event.date < " => $courseDate, "Event.date > " => $lowerDate);

		$data = $this->find("all", array("conditions" => $conditions));
		if(count($data) < 5)
			return false;
		else
			return true;
	}
	// Compute inverse mean inverse time
	function meanTimeByCourse($course_id) {
		$conditions = array("course_id = " => $course_id);
		$data = $this->find("all", array("conditions" => $conditions));
		$meanTime = 0;
		$counter = 0;
		foreach ($data as $value=>$key) {
			if($key["Result"]["time"] != "NULL") {
				
				$hour = substr($key["Result"]["time"],0,2);
				$min  = substr($key["Result"]["time"],3,2);
				$sec  = substr($key["Result"]["time"], 6,2);
				$meanTime = $meanTime + $this->toSec($key["Result"]["time"]);
				$counter += 0;
			}
		}
		if($counter == 0)
			return NULL;
		else
			return $meanTime / $counter;
	}
	function meanPoints($date) {
		$users = $this->User->find('all', array("conditions"=>"id < 50"));
		foreach ($users as $user) {
			$point = $this->meanPointsByUser($user["User"]["id"]);
			if($point != NULL)
				$points[$user["User"]["name"]] = $point;
		}
		arsort($points);
		return $points;
	}
	function meanPointsByUser($course_id, $user_id) {
		$courseDate = $this->Course->getDate($course_id);
		$lowerDate  = strtotime($courseDate) - 2 * 86400 * 365;
		$lowerDate  = date('Y-m-d h:i:s', $lowerDate);

		$conditions = array("user_id = " => $user_id, "status = " => 'ok', "time > " => 0, "Event.date < " =>$courseDate, "Event.date > " => $lowerDate);
		$data = $this->find("all", array("conditions" => $conditions));
		$meanPoints = 0;
		$counter = 0;
		foreach ($data as $value=>$key) {
			$currPoints = $key["Result"]["points"];
			if($currPoints != 0){
				$meanPoints = $meanPoints + $currPoints;
				$counter += 1;
			}
		}
		// Need 3 races to count as a valid user
		if($counter < 3)
			return NULL;
		else 
			return $meanPoints / $counter;
	}
	function numValidRunners($course_id) {
		$entries = $this->findAllByCourseId($course_id);
		$counter = 0;
		foreach ($entries as $entry) {
			$user_id = $entry["Result"]["user_id"];
			if($entry["Result"]["time"] != "00:00:00" && $entry["Result"]["time"] != NULL && $entry["Result"]["status"] == 'ok' && $this->isValidRunner($course_id, $user_id))
			{
				$counter += 1;
			}
		}
		return $counter;
	}

	function meanIncomingPoints($course_id) {
		$entries = $this->findAllByCourseId($course_id);
		$meanPoints = 0;
		$counter = 0;
		foreach ($entries as $entry) {
			$currPoints = $this->meanPointsByUser($course_id, $entry["Result"]["user_id"]);
			if($currPoints != NULL && $entry["Result"]["time"] != NULL && $entry["Result"]["time"] != "00:00:00" && $entry["Result"]["status"] == 'ok') {
				$meanPoints += $currPoints;
				$counter += 1;
			}
		}
		if($counter == 0)
			return NULL;
		else
			return $meanPoints / $counter;
	}
	function points($course_id){
		$entries = $this->findAllByCourseId($course_id);
		$meanIncoming = $this->meanIncomingPoints($course_id);
		$meanTime = $this->invMeanInvTimeByCourse($course_id);
		echo "mean time: " . $this->meanTimeByCourse($course_id) . "<br>";
		echo "mean points: " . $meanIncoming;
		$points = array();
		foreach($entries as $entry) {
			$time = $this->toSec($entry["Result"]["time"]);
			$user = $entry["Result"]["user_id"];
			$points[$user] = $meanTime/$time * $meanIncoming;
		}
		return $points;
	}
	function toSec($time) {
		if($time == "NULL")
			return null;
		$hour = substr($time,0,2);
		$min  = substr($time,3,2);
		$sec  = substr($time, 6,2);
		return mktime($hour, $min, $sec) - mktime(0,0,0);
	}

	function isAuthorized($id, $user) {
		$result = $this->find('first', array('conditions' => array('Result.id' => $id), 'contain' => array('Course.Event.id')));
		return $this->Course->Event->Organizer->isAuthorized($result["Course"]["Event"]["id"], $user);
	}
	
	/**
	* Returns a map of people who have attended multiple events recently to the number of events attended, ordered by who has come the most
	*/
	function findRecentAttendance($from, $minimumRaces = null) {
	   $from = Sanitize::escape($from->format('Y-m-d H:i:s'));
	   $resultSet = $this->query("SELECT COUNT(`Result`.`user_id`) AS `User.attended`, `Result`.`user_id` FROM `results` AS `Result` LEFT JOIN `courses` AS `Course` ON (`Result`.`course_id` = `Course`.`id`) LEFT JOIN `events` AS `Event` ON (`Event`.`id` = `Course`.`event_id`) WHERE `Event`.`date` > '$from' GROUP BY `Result`.`user_id` ORDER BY COUNT(`Result`.`user_id`) DESC");
	   $map = array();
	   foreach($resultSet as $result) {
	       if(!$minimumRaces || $result[0]['User.attended'] >= $minimumRaces) {
	           $map[$result['Result']['user_id']] = $result[0]['User.attended'];
	       }
	   }
	   
	   return $map;
	}
}
?>
