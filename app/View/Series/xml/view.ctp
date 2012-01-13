<?php
//IOF XML
//echo $xml->serialize($event, array('format' => 'tags'));
$courses = NULL;//$event["Course"];

// Write 
$eventClassification = "Local";
$eventName  = $series["Series"]["name"]; //$event["Event"]["name"];
$eventDate  = 1;//substr($event["Event"]["date"], 0, 10);
$eventClock = 1;//substr($event["Event"]["date"], 11, 13);

echo "<!DOCTYPE Event SYSTEM \"IOFdata_myO.dtd\">\n";
echo "<Event>\n";
echo $this->Xml->elem('EventId','', $series["Series"]["acronym"]);
echo $this->Xml->elem('Name',   '', $series["Series"]["name"]);
echo $this->Xml->elem('EventClassificationId', '', $eventClassification);
// Date
echo "<StartDate>\n";
echo $this->Xml->elem('Date', array('dateFormat' =>"YYYY-MM-DD"), substr($start_date,0,10));
echo "</StartDate>\n";
echo "<FinishDate>\n";
echo $this->Xml->elem('Date', array('dateFormat' =>"YYYY-MM-DD"), substr($end_date,0,10));
echo "</FinishDate>\n";
// Officials
foreach($organizers as $organizer) {
   echo "<EventOfficial>\n";
   echo $this->Xml->elem('EventOfficialRole', '', $organizer["role"]);
   echo "<Person>\n";
   echo $this->Xml->elem('PersonName', '', $organizer["name"]);
   echo $this->Xml->elem('PersonId', '', $organizer["id"]);
   echo "</Person>\n";
   echo "</EventOfficial>\n";
}
// Club
echo "<Organiser>\n";
echo "<Club>\n";
echo $this->Xml->elem('ShortName', '', "GVOC"); // TODO
echo "</Club>\n";
echo "</Organiser>\n";
// List of events
foreach($events as $event) {
   $eventDate  = substr($event["Event"]["date"], 0, 10);
   $eventClock = substr($event["Event"]["date"], 11, 13);
   echo "<EventRace>\n";
   echo $this->Xml->elem('EventRaceId', '',$event["Event"]["id"]);
   echo $this->Xml->elem('Name', '',$event["Event"]["name"]);
   echo "<RaceDate>\n";
   echo $this->Xml->elem('Date', array('dateFormat' =>"YYYY-MM-DD"), $eventDate);
   echo $this->Xml->elem('Clock', '', $eventClock);
   echo "</RaceDate>\n";
   echo "</EventRace>\n";

}



// Website
$currUrl = Router::url("", true);
$currUrl = preg_replace('/iofxml/', 'view', $currUrl);
echo $this->Xml->elem('WebURL', '', $currUrl);
// Information
echo "<SpecialInfo>\n";
echo $this->Xml->elem('SI_Title', '', 'Information');
echo $this->Xml->elem('SI_Content', '', "<![CDATA[".$series["Series"]["description"]."]]>");
echo "</SpecialInfo>\n";

echo "</Event>\n";


#print_r($courses);
//echo $this->Xml->elem('count', array('namespace' => 'myNameSpace'), 'content');
?>
