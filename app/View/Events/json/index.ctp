<?php
$this->layout = null;
$jsonEvents = array();
foreach($events as $event) {
	$jsonEvent["id"] = $event["Event"]["id"];
	$jsonEvent["title"] = $event["Series"]["acronym"]." - ".$event["Event"]["name"];
	$jsonEvent["start"] = $this->Time->toUnix($event["Event"]["utc_date"]);
	//$jsonEvent["end"] = $time->toUnix($event["Event"]["date"]) + 3600;
	$jsonEvent["allDay"] = false;
	$jsonEvent["url"] = $event["Event"]["url"];
	if(!empty($event["Series"]["color"])) {
		$jsonEvent["textColor"] = $event["Series"]["color"];
	} else $jsonEvent["textColor"] = "#dd5511";
	$jsonEvent["color"] = "#ffffff";
	
	array_push($jsonEvents,$jsonEvent);
}
// TODO-RWP Caching json
echo json_encode($jsonEvents);
?>
