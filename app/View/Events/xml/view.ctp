<?php
$courses = $event["Course"];
$organizers = $event["Organizer"];

$eventClassification = "Local";
$eventName  = $event["Event"]["name"];
$eventDate  = substr($event["Event"]["date"], 0, 10);
$eventClock = substr($event["Event"]["date"], 11, 13);

// Set up declaration
$implementation = new DOMImplementation();
$dtd = $implementation->createDocumentType('Event','','IOFdata_myO.dtd');
$xml = $implementation->createDocument('', '', $dtd);

$wrapper = $xml->createElement("Wrapper");
$xml->appendChild($wrapper);

//////////////
// ClubList //
//////////////
/*
$cl = $xml->createElement("ClubList"); 
$wrapper->appendChild($cl);
$c = $cl->appendChild($xml->createElement("Club"));
$c->appendChild($xml->createElement("ClubId", "1234567"));
$c->appendChild($xml->createElement("Name", "Greater Vancouver Orienteering Club"));
$c->appendChild($xml->createElement("ShortName", "GVOC"));
$c->appendChild($xml->createElement("CountryId", "CAN"));
 */

///////////
// Event //
///////////
$el = $wrapper->appendChild($xml->createElement("EventList"));
$e = $el->appendChild($xml->createElement("Event"));
$e->appendChild($xml->createElement('EventId', $event["Event"]["id"]));
$e->appendChild($xml->createElement('Name', htmlentities($event["Event"]["name"])));
$e->appendChild($xml->createElement('EventClassificationId', $eventClassification));
$startDate = $xml->createElement('StartDate');
$endDate = $xml->createElement('FinishDate');
$e->appendChild($startDate);
$e->appendChild($endDate);
$startDate->appendChild($xml->createElement('Date', $eventDate));
$startDate->appendChild($xml->createElement('Clock', $eventClock));
$endDate->appendChild($xml->createElement('Date', $eventDate));
$endDate->appendChild($xml->createElement('Clock', $eventClock));

//Officials
foreach($organizers as $organizer) {
   $o = $xml->createElement("EventOfficial");
   $p = $xml->createElement("Person");
   $o->appendChild($p);
   $p->appendChild($xml->createElement('PersonName', htmlentities($organizer["User"]["name"])));
   $p->appendChild($xml->createElement('PersonId', $organizer["User"]["id"]));
   $e->appendChild($o);
}

// Club
$o = $xml->createElement("Organizer");
$e->appendChild($o); 
$c = $xml->createElement("Club");
$o->appendChild($c); 
$c->appendChild($xml->createElement("ShortName", "GVOC"));

// Website
$currUrl = Router::url("", true);
$currUrl = preg_replace('/.xml/', '', $currUrl);
$e->appendChild($xml->createElement("WebURL", "$currUrl"));

// Information
$i = $xml->createElement("SpecialInfo");
$e->appendChild($i); 
$i->appendChild($xml->createElement("SI_Title", "Information"));
$i->appendChild($xml->createElement("SI_Content", htmlspecialchars($this->Markdown->render($event["Event"]["description"]))));

/////////////
// Classes //
/////////////
/*
$cl = $wrapper->appendChild($xml->CreateElement("ClassData"));
foreach ( $courses as $course) {
   $c = $cl->appendChild($xml->createElement("Class"));
   $c->appendChild($xml->createElement("ClassId",$course["id"]));
   $c->appendChild($xml->createElement("Name", $course["name"]));
   $c->appendChild($xml->createElement("ClassShortName", $course["name"]));
}
 */

/////////////
// Entries //
/////////////
/*
$e = $wrapper->appendChild($xml->createElement("EntryList"));
$e->appendChild($xml->createElement("IOFVersion", "2.0.3"));
$ce = $e->appendChild($xml->createElement("ClubEntry"));
$c = $ce->appendChild($xml->createElement("Club"));
$c->appendChild($xml->createElement("ClubId", "1234567"));
$c->appendChild($xml->createElement("Name", "Greater Vancouver Orienteering Club"));
$c->appendChild($xml->createElement("ShortName", "GVOC"));
$c->appendChild($xml->createElement("CountryId", "CAN"));
foreach ( $courses as $course) {
   foreach ($course["Result"] as $runner) {
      $entry = $ce->appendChild($xml->createElement("Entry"));
      $p = $entry->appendChild($xml->createElement("Person"));
      $pn = $p->appendChild($xml->createElement("PersonName"));
      $pn->appendChild($xml->createElement("Family",$runner["User"]["name"]));
//      $pn->appendChild($xml->createElement("Given", "Thomas"));
      $cc = $entry->appendChild($xml->createElement("CCard"));
      $cc->appendChild($xml->createElement("CCardId",$runner["User"]["si_number"]));
      $cc->appendChild($xml->createElement("PunchingUnitType", "SI"));
      $ec = $entry->appendChild($xml->createElement("EntryClass"));
      $ec->appendChild($xml->createElement("ClassShortName", $course["name"]));
   }
}
 */
///////////////
// StartList //
///////////////
$sl = $wrapper->appendChild($xml->createElement("StartList"));
foreach ( $courses as $course) {
   $c = $sl->appendChild($xml->createElement("ClassStart"));
   $c->appendChild($xml->createElement("ClassShortName", $course["name"]));
   foreach ($course["Result"] as $runner) {
      $ps = $c->appendChild($xml->createElement("PersonStart"));
      $p = $ps->appendChild($xml->createElement("Person"));
      $pn = $p->appendChild($xml->createElement("PersonName"));
      $pn->appendChild($xml->createElement("Family", htmlentities($runner["User"]["name"])));

      $s = $ps->appendChild($xml->createElement("Start"));
      $s->appendChild($xml->createElement("CCardId",$runner["User"]["si_number"]));

      $club = $ps->appendChild($xml->createElement("Club"));
      $club->appendChild($xml->createElement("ClubId", "1"));
      $club->appendChild($xml->createElement("Name", "Greater Vancouver Orienteering Club"));
      $club->appendChild($xml->createElement("ShortName", "GVOC"));
      $c->appendChild($xml->createElement("CountryId", "CAN"));
   }
}

// Courses
/*
//echo $this->Xml->elem('count', array('namespace' => 'myNameSpace'), 'content');
*/
echo $xml->saveXML();
?>
