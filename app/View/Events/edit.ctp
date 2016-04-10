<header class="page-header">
    <h1><?php echo $type ?> Event</h1>
</header>
<?php
if(!empty($this->data['Event']['date'])) {
    $parts = explode(' ', $this->data['Event']['date']);
    $date = $parts[0];
    // Remove the seconds from the time
    $time = substr($parts[1], 0, -3);
} else {
    $date = $time = "";
}

if(!empty($this->data['Event']['finish_date'])) {
    $parts = explode(' ', $this->data['Event']['finish_date']);
    $finishDate = $parts[0];
    $finishTime = substr($parts[1], 0, -3);
} else {
    $finishTime = $finishDate = "";
}

echo $this->Form->create('Event', array('class' => 'form-horizontal', 'data-validate' => 'ketchup', 'url' => 'edit'));
// Hidden JSON encoded organizer data from the edit organizers UI
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('name', array('data-validate' => 'validate(required)', 'required' => 'required')); ?>
<div class="form-group">
    <label class="control-label col-sm-2">Start Time</label>
    <div class="col-sm-10">
        <div class="form-inline">
            <div class="form-group inline-validated">
                <?php echo $this->Form->text('date', array('value' => $date, 'size' => '10', 'maxLength' => 10, 'data-validate' => 'validate(date, required)', 'placeholder' => 'yyyy-mm-dd', 'class' => 'form-control date-picker', 'data-format' => 'YYYY-MM-DD', 'div' => false, 'label' => false)) ?>
            </div>
            <div class="form-group inline-validated">
                <?php echo $this->Form->text('time', array('placeholder' => 'hh:mm', 'size' => '5', 'data-validate' => 'validate(required, time)', 'maxLength' => 5, 'data-format' => 'HH:mm', 'value' => $time, 'div' => false, 'class' => 'form-control time-picker', 'label' => false)) ?>
            </div>
            <span class="inline-validated-help"> (24 hour format)</span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2">End Time</label>
    <div class="col-sm-10">
        <div class="form-inline">
            <div class="form-group inline-validated">
                <?php echo $this->Form->text('finish_date', array('size' => '10', 'value' => $finishDate, 'maxLength' => 10, 'placeholder' => 'yyyy-mm-dd', 'data-validate' => 'validate(date, date_after(EventDate, EventTime, EventFinishDate, EventFinishTime))', 'class' => 'form-control date-picker', 'data-format' => 'YYYY-MM-DD', 'div' => false, 'label' => false)) ?>
            </div>
            <div class="form-group inline-validated">
                <?php echo $this->Form->text('finish_time', array('size' => '5', 'placeholder' => 'hh:mm', 'maxLength' => 5, 'value' => $finishTime, 'data-validate' => 'validate(time, requires(EventFinishDate, finish date))', 'data-format' => 'HH:mm', 'div' => false, 'class' => 'form-control time-picker', 'label' => false)) ?>
            </div>
            <span class="inline-validated-help"> (optional)</span>
        </div>
    </div>
</div>

<?php echo $this->Form->input('event_classification_id', array('empty' => 'Choose classification', 'required' => 'required', 'data-validate' => 'validate(required)'));
?>

<?php
$urlFieldOptions = array('size' => '80', 'label' => false, 'div' => false, 'class' => 'form-control', 'data-validate' => 'validate(url_or_empty)', 'type' => 'url');
$urlFields = array(
    array(
        'name' => 'Redirect URL',
        'help' => 'If you want to use a third party website for the event, enter the URL for the event page here. NOTE: People accessing your event may be automatically redirected to the other website, depending on where on WhyJustRun they come from.',
        'field' => 'custom_url',
        'options' => array('placeholder' => 'Only use if all info is on external website'),
    ),
    array(
        'name' => 'Registration URL',
        'help' => 'If you want to use a third party registration system like Zone4, enter the URL to the registration page here.',
        'field' => 'registration_url',
        'options' => array(),
    ),
    array(
        'name' => 'Results URL',
        'help' => 'If you post results on a third party website like WinSplits, enter the URL to the results page here after the event.',
        'field' => 'results_url',
        'options' => array(),
    ),
    array(
        'name' => 'RouteGadget URL',
        'help' => 'If you have posted a RouteGadget for the event, enter the URL to the RouteGadget page here after the event.',
        'field' => 'routegadget_url',
        'options' => array(),
    ),
    array(
        'name' => 'Facebook URL',
        'help' => 'If you have a Facebook Event, add the URL here.',
        'field' => 'facebook_url',
        'options' => array(),
    ),
    array(
        'name' => 'Attackpoint URL',
        'help' => 'If you have an Attackpoint Event, add the URL here.',
        'field' => 'attackpoint_url',
        'options' => array(),
    ),
);

foreach ($urlFields as $urlField) {
?>
<div class="form-group">
<label class="control-label col-sm-2"><?php echo $urlField['name'] ?>
    <span data-toggle="tooltip" data-container="body" class="wjr-help-tooltip" title="<?php echo $urlField['help'] ?>">
                <span class="glyphicon glyphicon-question-sign"></span>
            </span>
    </label>
    <div class="col-sm-10">
        <?php echo $this->Form->input($urlField['field'], array_merge($urlFieldOptions, $urlField['options'])) ?>
    </div>
</div>
<?php
}
?>

<fieldset class="form-group">
    <label for="EventDescription" class="control-label col-sm-2">Description</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('description', array('label' => false, 'class' => 'form-control wjr-wysiwyg', 'rows' => 12, 'div' => false)); ?>
    </div>
</fieldset>
<?php
echo $this->Form->input('series_id', array('empty' => 'Choose the event series'));
echo $this->Form->input('map_id', array('empty' => 'Choose the event map'));
?>

<?php
$this->Form->unlockField('Event.organizers');
$this->Form->unlockField('Event.courses');
$coursesInput = $this->Form->hidden('courses', array('value' => $this->data["Event"]["courses"], 'data-bind' => 'value: ko.toJSON(courses)'));
$organizersInput = $this->Form->hidden('organizers', array('value' => $this->data["Event"]["organizers"], 'data-bind' => 'value: ko.toJSON(organizers)'));
$params = array('coursesInput' => $coursesInput, 'organizersInput' => $organizersInput);
echo $this->element('Events/edit_courses_organizers', $params);
?>

<div class="form-group">
    <label class="control-label col-sm-2">Number of Participants</label>
    <div class="col-sm-10">
        <div class="form-inline">
            <div class="form-group">
                <?php echo $this->Form->input('number_of_participants', array('type' => 'number', 'min' => '0', 'max' => '4294967295', 'class' => 'form-control', 'div' => false, 'label' => false)) ?>
            </div>
            (Optional: used for insurance purposes, overrides participant count from event results)
        </div>
    </div>
</div>

<fieldset class="form-group">
    <label class="control-label col-sm-2">Meeting location</label>
    <div class="col-sm-10">
        <?php
        echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
        echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng')));

        $this->Form->unlockField('Event.lat');
        $this->Form->unlockField('Event.lng');
        ?>
        <div class="draggable-marker-map"
             data-lat-element="#EventLat"
             data-lng-element="#EventLng"
             data-zoom="10"
             style="height: 400px; width: 100%">
        </div>
        <p class="help-block">Drag the marker to the meeting location</p>
    </div>
</fieldset>

<div class="form-group">
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'col-sm-offset-2 col-sm-10'))) ?>
</div>
