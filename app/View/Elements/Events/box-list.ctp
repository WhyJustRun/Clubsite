<?php 
// Filter can either be 'upcoming' or 'past'
$events = $this->requestAction('events/'.$filter.'/'.$limit);
foreach($events as $event) {
	$date = new DateTime($event["Event"]["date"]);	
	// Get geocoding data
	$geocodeData = $this->Geocode->lookUp($event["Event"]["lat"], $event["Event"]["lng"]);
	if(!empty($geocodeData["neighbourhood"])) {
		$city = $geocodeData["city"]." (".$geocodeData["neighbourhood"].")";
	} else {
		$city = $geocodeData["city"];
	}
	
    echo $this->element('Events/box', array('name' => $event["Series"]["name"], 'location' => $event["Event"]["name"], 'date' => $date->format('D F jS g:ia'), 'city' => $city, 'link' => $event["Event"]["url"], 'style' => $event["Series"]["id"], 'mapId' => $event["Event"]["map_id"])); 
}
?>
