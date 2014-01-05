<?php
// Params: $event
$registrationURL = $event['Event']['registration_url'];
if($event["Event"]["completed"] === true) {
    echo $this->element('Courses/course_maps', array('courses' => $event["Course"]));
} else if($registrationURL) { ?>
    <header>
    <h2>Registration</h2>
    </header>
    <div class="btn-group">
        <a href="<?= $registrationURL ?>" class="btn btn-large btn-success">
            Register at <?= parse_url($registrationURL, PHP_URL_HOST) ?>
        </a>
    </div>
<?php
} else if(count($event["Course"]) > 0) {
    echo $this->element('Events/course_registration', array('courses' => $event['Course']));
} ?>
