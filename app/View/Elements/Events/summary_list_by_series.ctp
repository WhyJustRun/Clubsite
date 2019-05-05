<?php
$ongoing = $this->element('Events/box-list', array('filter' => 'ongoing', 'limit' => '-1'));

if(!empty($ongoing)) {
    echo "<h3>Ongoing</h3>";
    echo $ongoing;
}

$seriesSet = $this->requestAction('series/index');

$upcoming = array();
foreach($seriesSet as $series) {
    if (!$series["Series"]["is_current"]) {
        continue;
    }

    $upcoming[$series["Series"]["name"]] = $this->element('Events/box-list',
                array('filter' => 'upcoming', 'limit' => '2', 'series_id' => $series["Series"]["id"]));
}
$upcoming["Other"] = $this->element('Events/box-list', array('filter' => 'upcoming', 'limit' => '2', 'series_id' => 0));

echo "<h3>Upcoming</h3>";
foreach($upcoming as $title => $content) {
    if(!empty($content)) {
        echo "<h4>${title}</h4>";
        echo $content;
    }
}

$past = array();
foreach($seriesSet as $series) {
    if (!$series["Series"]["is_current"]) {
        continue;
    }

    $past[$series["Series"]["name"]] = $this->element('Events/box-list',
                array('filter' => 'past', 'limit' => '1', 'series_id' => $series["Series"]["id"]));
}
$past["Other"] = $this->element('Events/box-list', array('filter' => 'past', 'limit' => '1', 'series_id' => 0));

echo "<h3>Past</h3>";
foreach($past as $title => $content) {
    if(!empty($content)) {
        echo "<h4>${title}</h4>";
        echo $content;
    }
}
