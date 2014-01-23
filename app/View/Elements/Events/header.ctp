<?php
// Params: $event, $canEdit (boolean)
$formattedEventDate = $this->TimePlus->formattedEventDate($event);
?>
<header class="page-header">
<div class="pull-right btn-toolbar">
    <div class="btn-group">
        <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
            <span class="glyphicon glyphicon-download-alt"></span> Export
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <?php if($canEdit) { ?>
            <li><a href="/events/printableEntries/<?= $event['Event']['id'] ?>">Printable List</a></li>
            <?php } ?>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/2.0.3/events/<?= $event['Event']['id'] ?>/start_list.xml">Start List (IOF XML 2)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/2.0.3/events/<?= $event['Event']['id'] ?>/result_list.xml">Result List (IOF XML 2)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $event['Event']['id'] ?>/result_list.xml">Result List (IOF XML 3)</a></li>
            <li><a href="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $event['Event']['id'] ?>/entry_list.xml">Entry List (IOF XML 3)</a></li>
        </ul>
    </div>
    <?php if($canEdit) { ?>
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

<h2 class="event-header"><?= $formattedEventDate ?></h2>
<? if(!empty($event["Event"]["custom_url"])) {?>
<div class="danger alert alert-danger">
    <h3 class="event-header">This event has an external website: <?= $this->Html->link($event["Event"]["custom_url"])?></h3>
</div>
<? } ?>
</header>
