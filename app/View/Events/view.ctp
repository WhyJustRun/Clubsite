<?php
$tz = Configure::read('Club.timezone');
$startDate = new DateTime($event["Event"]["date"], $tz);
$finishDate = $event['Event']['finish_date'] ? new DateTime($event["Event"]["finish_date"], $tz) : null;
if($event["Event"]["finish_date"] != NULL) {
    if($startDate->format('D F jS') === $finishDate->format('D F jS')) {
        $dateFormatted = $startDate->format('F jS Y g:ia') . " - " . $finishDate->format('g:ia');
    } else {
        $dateFormatted = $startDate->format('F jS Y g:ia') . " - " . $finishDate->format('F jS Y g:ia');
    }
} else {
    $dateFormatted = $startDate->format('F jS Y g:ia');
}

$dynamicText = "Orienteering event taking place: " . $dateFormatted . ".";

$this->OpenGraph->addTag("og:type", "event");
$this->OpenGraph->addTag("og:url", $this->Html->url($event['Event']['url'], true));
$this->OpenGraph->addTag("og:description", "$dynamicText Orienteering is an exciting sport for all ages and fitness levels that involves reading a detailed map and using a compass to find checkpoints.");
$this->OpenGraph->addTag("og:title", $event['Event']['name']);
$this->OpenGraph->addTag("og:image", $this->Html->url('/img/orienteering_symbol.png', true));
$tz = Configure::read('Club.timezone');
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

<header class="page-header">
<div class="pull-right btn-toolbar">
    <div class="btn-group">
        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
            <span class="glyphicon glyphicon-download-alt"></span> Export
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php if($edit) { ?>
            <li><a href="/events/printableEntries/<?= $event['Event']['id'] ?>">Printable List</a></li>
            <?php } ?>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/2.0.3/events/<?= $event['Event']['id'] ?>/start_list.xml">Start List (IOF XML 2)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/2.0.3/events/<?= $event['Event']['id'] ?>/result_list.xml">Result List (IOF XML 2)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $event['Event']['id'] ?>/result_list.xml">Result List (IOF XML 3)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $event['Event']['id'] ?>/entry_list.xml">Entry List (IOF XML 3)</a></li>
        </ul>
    </div>
    <?php if($edit) { ?>
    <div class="btn-group">
        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
            <span class="glyphicon glyphicon-cog"></span> Edit
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="/events/edit/<?= $event['Event']['id'] ?>">Event</a></li>
            <li><a href="/events/edit/<?= $event['Event']['id'] ?>">Courses</a></li>
            <li><a href="/events/editResults/<?= $event['Event']['id'] ?>">Registrations/Results</a></li>
            <li><a href="/events/uploadMaps/<?= $event['Event']['id'] ?>">Course Maps</a></li>
        </ul>
    </div>

    <div class="btn-group">
        <a class="btn btn-danger" href="/events/delete/<?= $event['Event']['id'] ?>" onclick='return confirm("Delete this event (including any defined organizers, courses and results)?");'><span class="glyphicon glyphicon-trash"></span></a>
    </div>
    <?php } ?>
</div>

<h1 class="series-<?= $event["Series"]["id"]; ?> event-header"><?= $event["Event"]["name"]; ?> <small class="series-<?= $event["Series"]["id"]; ?> event-header"><?= $event["Series"]["name"] ?></small></h1>

<h2 class="event-header"><?php 
    echo $dateFormatted;
    ?></h2>
<? if(!empty($event["Event"]["custom_url"])) {?>
<div class="danger alert alert-danger">
    <h3 class="event-header">This event has an external website: <?= $this->Html->link($event["Event"]["custom_url"])?></h3>
</div>
<? } ?>
</header>

<div class="row">
    <?php if(!$event["Event"]["results_posted"] && !$event['Event']['results_url'] && !$event['Event']['routegadget_url']) { 
    // Show event information
    ?>
    <div class="col-sm-8">
        <?php echo $this->element('Events/info', array('event' => $event)); ?>
    </div>

    <?php
    $registrationURL = $event['Event']['registration_url'];
    if($event["Event"]["completed"] === true) { ?>
    <div class="results col-sm-4">
        <?php echo $this->element('Courses/course_maps', array('courses' => $event["Course"])); ?>
    </div>
    <?php } else if($registrationURL) { ?>
    <div class="results col-sm-4">
        <header>
        <h2>Registration</h2>
        </header>
        <div class="btn-group">
            <a href="<?= $registrationURL ?>" class="btn btn-large btn-success">
                Register at <?= parse_url($registrationURL, PHP_URL_HOST) ?>
            </a>
        </div>
    </div>
    <?php
    } else if(count($event["Course"]) > 0) {
        echo $this->element('Events/course_registration', array('courses' => $event['Course']));
    } ?>
    <?php } else {
    // Show results page
    ?>
    <div class="col-sm-6">
        <?= $this->element('Events/results_section', array('event' => $event)) ?> 
    </div>
    <div class="col-sm-6">
        <?= $this->element('Events/info', array('event' => $event)) ?>
    </div>
    <?php } ?>
</div>

<?= $this->element('Events/flickr_photos', array('eventId' => $event['Event']['id'])); ?>
