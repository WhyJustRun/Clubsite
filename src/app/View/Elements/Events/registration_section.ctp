<?php
// Params: $event, $canEdit
$registrationURL = $event['Event']['registration_url'];

if (!empty($event['ResultList']['id']) && $event['ResultList']['status'] === 'live' && $canEdit) {
    echo "<h2>Live Results</h2>";
    echo $this->element('LiveResults/visibility_button', array('event' => $event));
}

if($event["Event"]["completed"] === true) {
    echo $this->element('Courses/course_maps', array('courses' => $event["Course"]));
} else if($registrationURL) { ?>
    <header>
    <h2>Registration</h2>
    </header>
    <div class="btn-group">
        <a href="<?php echo $registrationURL ?>" class="btn btn-large btn-success">
            Register at <?php echo parse_url($registrationURL, PHP_URL_HOST) ?>
        </a>
    </div>
<?php
} else if(count($event["Course"]) > 0) {
?>
<header>
    <h2>Course Registration</h2>
<?php
if (!empty($event['Event']['registration_deadline'])) {
    if ($event['Event']['registration_open']) {
        $deadlineDate = new DateTime($event['Event']['registration_deadline']);
        $deadlineDateFormatted = "Deadline " . $deadlineDate->format('M jS g:ia');
    } else {
        $deadlineDateFormatted = "Registration is Closed";
    }
?>
    <h4 class="reg-deadline-header"><?php echo $deadlineDateFormatted ?></h4>
<?php
}
?>
</header>
<?php
    echo $this->element('Events/course_registration', array('courses' => $event['Course'], 'reg_open' => $event['Event']['registration_open']));
} ?>
