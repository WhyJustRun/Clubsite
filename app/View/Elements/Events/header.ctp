<?php
// Params: $event, $canEdit (boolean)
$formattedEventDate = $this->TimePlus->formattedEventDate($event);
?>
<header class="page-header">
    <div itemscope itemtype="http://schema.org/Place">
        <meta itemprop="name" content="<?php echo htmlentities($event['Event']['name']) ?>" />
        <div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
            <meta itemprop="latitude" content="<?php echo $event['Event']['lat'] ?>" />
            <meta itemprop="longitude" content="<?php echo $event['Event']['lng'] ?>" />
        </div>
    </div>
    <div class="pull-right btn-toolbar">
        <div class="btn-group">
            <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-download-alt"></span> Export
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <?php if($canEdit) { ?>
                <li><a href="/events/printableEntries/<?php echo $event['Event']['id'] ?>">Printable List</a></li>
                <?php } ?>
                <li><a href="<?php echo Configure::read('Rails.domain') ?>/iof/2.0.3/events/<?php echo $event['Event']['id'] ?>/start_list.xml">Start List (IOF XML 2)</a></li>
                <li><a href="<?php echo Configure::read('Rails.domain') ?>/iof/2.0.3/events/<?php echo $event['Event']['id'] ?>/result_list.xml">Result List (IOF XML 2)</a></li>
                <li><a href="<?php echo Configure::read('Rails.domain') ?>/iof/3.0/events/<?php echo $event['Event']['id'] ?>/result_list.xml">Result List (IOF XML 3)</a></li>
                <li><a href="<?php echo Configure::read('Rails.domain') ?>/iof/3.0/events/<?php echo $event['Event']['id'] ?>/entry_list.xml">Entry List (IOF XML 3)</a></li>
            </ul>
        </div>
        <?php if($canEdit) { ?>
        <div class="btn-group">
            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-cog"></span> Edit
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="/events/edit/<?php echo $event['Event']['id'] ?>">Event</a></li>
                <li><a href="/events/edit/<?php echo $event['Event']['id'] ?>">Courses</a></li>
                <li><a href="/events/editResults/<?php echo $event['Event']['id'] ?>">Registrations/Results</a></li>
                <li><a href="/events/uploadMaps/<?php echo $event['Event']['id'] ?>">Course Maps</a></li>
            </ul>
        </div>

        <div class="btn-group">
            <a class="btn btn-danger" href="/events/delete/<?php echo $event['Event']['id'] ?>" onclick='return confirm("Delete this event (including any defined organizers, courses and results)?");'><span class="glyphicon glyphicon-trash"></span></a>
        </div>
        <?php } ?>
    </div>

    <h1 class="series-<?php echo $event["Series"]["id"]; ?> event-header"><?php echo $event["Event"]["name"]; ?> <small class="series-<?php echo $event["Series"]["id"]; ?> event-header"><?php echo $event["Series"]["name"] ?></small></h1>

    <h2 class="event-header"><?php echo $formattedEventDate ?></h2>
</header>
