<?php
// Params: $event
?>
<div class="results">
    <header>
        <h2>Results</h2>
    </header>

    <?php
    echo $this->element('Events/files', array('id' => $event["Event"]["id"]));
    if ($event['Event']['results_posted']) {
        echo $this->element('Events/results_list', array('eventId' => $event['Event']['id']));
    }

    echo $this->element('Events/results_links', array('event' => $event));
    echo $this->element('Courses/course_maps', array('courses' => $event["Course"]));
    ?>
</div>
