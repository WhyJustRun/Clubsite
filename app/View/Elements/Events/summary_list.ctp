<?php
$events = array();
$events["Ongoing"] = $this->element('Events/box-list', array('filter' => 'ongoing', 'limit' => '-1'));
$events["Upcoming"] = $this->element('Events/box-list', array('filter' => 'upcoming', 'limit' => '4'));
$events["Past"] = $this->element('Events/box-list', array('filter' => 'past', 'limit' => '2'));

foreach($events as $title => $content) {
    if(!empty($content)) {
        echo "<h3>${title}</h3>";
        echo $content;
    }
}
