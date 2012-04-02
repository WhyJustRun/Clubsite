<?php 
// Filter can either be 'upcoming' or 'past'
$events = $this->requestAction('events/'.$filter.'/'.$limit);
foreach($events as $event) {
    $startDate = new DateTime($event["Event"]["date"]);	
    if($event["Event"]["finish_date"] != NULL) {
        $finishDate = new DateTime($event["Event"]["finish_date"]);	
        $finishDateFormated= $finishDate->format('D F jS g:ia');
        $dateFormated = $startDate->format('D F jS') . " - " . $finishDate->format('D F jS');
    }
    else {
        $dateFormated = $startDate->format('D F jS g:ia');
    }
    // Get geocoding data
    $geocodeData = $this->Geocode->lookUp($event["Event"]["lat"], $event["Event"]["lng"]);
    if(!empty($geocodeData["neighbourhood"])) {
        $city = $geocodeData["city"]." (".$geocodeData["neighbourhood"].")";
    } else {
        $city = $geocodeData["city"];
    }

    echo $this->element('Events/box', array('name' => $event["Series"]["name"], 'location' => $event["Event"]["name"], 'date' => $dateFormated, 'city' => $city, 'link' => $event["Event"]["url"], 'style' => $event["Series"]["id"], 'mapId' => $event["Event"]["map_id"])); 
}
?>
