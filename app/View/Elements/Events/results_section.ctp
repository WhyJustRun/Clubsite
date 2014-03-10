<?php
// Params: $event, $canEdit
?>
<div class="results">
    <header>
        <h2>Results</h2>
    </header>

    <?php
    if ($event['Event']['results_posted']) {
        echo $this->element('Events/results_list', array('eventId' => $event['Event']['id']));
    } else if (!empty($event['LiveResult']['id'])) {
        if ($canEdit) {
            echo $this->element('LiveResults/visibility_button', array('event' => $event));
        }

        if ($event['LiveResult']['visible']) {
            echo $this->element('LiveResults/result_list', array('event' => $event));
        }
    }

    echo $this->element('Events/results_links', array('event' => $event));
    echo $this->element('Courses/course_maps', array('event' => $event));
    ?>
</div>
