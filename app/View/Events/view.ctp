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
        <div class="results">
            <header>
                <h2>Results</h2>
            </header>

            <?php echo $this->element('Events/files', array('id' => $event["Event"]["id"])); ?>
            <?php if ($event['Event']['results_posted']) { ?>
            <div class="results-list">
                <?= $this->Html->script('result_viewer'); ?>                  
                <div id="list" class="result-list" data-result-list-url="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $event['Event']['id'] ?>/result_list.xml">
                    <div data-bind="foreach: courses">
                        <h3 data-bind="text: name"></h3>
                        <div data-bind="if: results().length == 0">
                            <p><b>No results</b></p>
                        </div>
                        <div data-bind="if: results().length > 0">
                            <table class="table table-striped table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Participant</th>
                                        <th data-bind="visible: isScore">Score Points</th>
                                        <th>Time</th>
                                        <th data-bind="visible: isTimed">Points</th>
                                    </tr>
                                </thead>
                                <tbody data-bind="foreach: results">
                                    <tr>
                                        <td data-bind="text: position || friendlyStatus"></td>
                                        <td><a data-bind="attr: { href: person.profileUrl }"><span data-bind="text: person.givenName + ' ' + person.familyName"></span></a></td>
                                        <td data-bind="visible: $parent.isScore, text: scores['Points']"></td>
                                        <td data-bind="text: time != null ? hours + ':' + minutes + ':' + seconds + ($parent.millisecondTiming ? '.' + milliseconds : '' ) : ''"></td>
                                        <td data-bind="visible: $parent.isTimed, text: scores['WhyJustRun']"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php
            $urlKeys = array('results_url' => 'Results at ', 'routegadget_url' =>  'RouteGadget at ');
            foreach ($urlKeys as $urlKey => $titlePrefix) {
                if (!empty($event['Event'][$urlKey]) && $url = $event['Event'][$urlKey]) { ?>
                    <div class="btn-group">
                        <a href="<?= $url ?>" class="btn btn-primary"><?= $titlePrefix.parse_url($url, PHP_URL_HOST) ?></a>
                    </div>
                    <br/><br/>
            <?php
                }
            } ?>
            <?= $this->element('Courses/course_maps', array('courses' => $event["Course"])); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <?php echo $this->element('Events/info', array('event' => $event)); ?>
    </div>
    <?php } ?>
</div>

<?= $this->element('Events/flickr_photos', array('eventId' => $event['Event']['id'])); ?>
