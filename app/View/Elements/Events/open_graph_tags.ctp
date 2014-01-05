<?php
// Params: $event
$tz = Configure::read('Club.timezone');
$startDate = new DateTime($event["Event"]["date"], $tz);
$finishDate = $event['Event']['finish_date'] ? new DateTime($event["Event"]["finish_date"], $tz) : null;

$dynamicText = "Orienteering event taking place: " . $this->TimePlus->formattedEventDate($startDate, $finishDate) . ".";

$this->OpenGraph->addTag("og:type", "event");
$this->OpenGraph->addTag("og:url", $this->Html->url($event['Event']['url'], true));
$this->OpenGraph->addTag("og:description", "$dynamicText Orienteering is an exciting sport for all ages and fitness levels that involves reading a detailed map and using a compass to find checkpoints.");
$this->OpenGraph->addTag("og:title", $event['Event']['name']);
$this->OpenGraph->addTag("og:image", $this->Html->url('/img/orienteering_symbol.png', true));
$this->OpenGraph->addTag("event:start_time", $startDate->format(DateTime::ISO8601));
if ($finishDate) {
    $this->OpenGraph->addTag('event:end_time', $finishDate->format(DateTime::ISO8601));
}
if (!empty($event['Event']['lat'])) {
    $this->OpenGraph->addTag("event:location:latitude", $event['Event']['lat']);
}

if (!empty($event['Event']['lng'])) {
    $this->OpenGraph->addTag("event:location:longitude", $event['Event']['lng']);
}
?>
