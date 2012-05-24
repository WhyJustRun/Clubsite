<?php 
// Filter can either be 'upcoming' or 'past'
$events = $this->requestAction('events/'.$filter.'/'.$limit);
foreach($events as $event) {
    $startDate = new DateTime($event["Event"]["date"]);	
    $finishDate = new DateTime($event["Event"]["finish_date"]);	
    if($event["Event"]["finish_date"] != NULL) {
        if($startDate->format('D F jS') === $finishDate->format('D F jS')) {
            $dateFormated = $startDate->format('D F jS g:ia') . " - " . $finishDate->format('g:ia');
        } else {
            $finishDateFormated = $finishDate->format('D F jS g:ia');
            $dateFormated = $startDate->format('D F jS') . " - " . $finishDate->format('D F jS');
        }
    }
    else {
        $dateFormated = $startDate->format('D F jS g:ia');
    }

    $classification = isset($event["EventClassification"]["name"]) ? $event["EventClassification"]["name"] : null;
    
    echo $this->element('Events/box', array('name' => $event["Series"]["name"], 'location' => $event["Event"]["name"], 'classification' => $classification, 'date' => $dateFormated, 'link' => $event["Event"]["url"], 'style' => $event["Series"]["id"], 'mapId' => $event["Event"]["map_id"])); 
}
?>
