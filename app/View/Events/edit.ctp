<header class="page-header">
    <h1><?= $type ?> Event</h1>
</header>
<?php 
if(!empty($this->data['Event']['date'])) {
    $parts = explode(' ', $this->data['Event']['date']);
    $date = $parts[0];
    $time = $parts[1];
} else {
    $date = $this->TimePlus->format("Y-m-d", date('c'));
    $time = $this->TimePlus->format("H:i:s", date('c'));
}

if(!empty($this->data['Event']['finish_date'])) {
    $parts = explode(' ', $this->data['Event']['finish_date']);
    $finishDate = $parts[0];
    $finishTime = $parts[1];
} else {
    $finishTime = $finishDate = "";
}

echo $this->Form->create('Event', array('class' => 'form-horizontal', 'data-validate' => 'ketchup', 'action' => 'edit'));
// Hidden JSON encoded organizer data from the edit organizers UI
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->hidden('organizers', array('value' => $this->data["Event"]["organizers"], 'data-bind' => 'value: ko.toJSON(organizers)'));
$this->Form->unlockField('Event.organizers');
echo $this->Form->hidden('courses', array('value' => $this->data["Event"]["courses"], 'data-bind' => 'value: ko.toJSON(courses)'));
$this->Form->unlockField('Event.courses');
echo $this->Form->input('name', array('data-validate' => 'validate(required)', 'required' => 'required')); ?>
<div class="control-group">
    <label class="control-label">Start Time</label>
    <div class="controls">
        <div>
            <?= $this->Form->text('date', array('value' => $date, 'maxLength' => 10, 'data-validate' => 'validate(required)', 'placeholder' => 'yyyy-mm-dd', 'class' => 'input-small date-picker', 'data-date-format' => 'yyyy-mm-dd', 'div' => false, 'label' => false)) ?><span class="add-on"> <?= $this->Form->text('time', array('placeholder' => 'hh:mm:ss', 'data-validate' => 'validate(required)', 'maxLength' => 8, 'value' => $time, 'div' => false, 'class' => 'input-small', 'label' => false)) ?> (24 hour format)</span>
            
        </div>
    </div>
</div>
<div class="control-group">
    <label class="control-label">End Time</label>
    <div class="controls">
        <div>
            <?= $this->Form->text('finish_date', array('value' => $finishDate, 'maxLength' => 10, 'placeholder' => 'yyyy-mm-dd', 'class' => 'input-small date-picker', 'data-date-format' => 'yyyy-mm-dd', 'div' => false, 'label' => false)) ?><span class="add-on"> <?= $this->Form->text('finish_time', array('placeholder' => 'hh:mm:ss', 'maxLength' => 8, 'value' => $finishTime, 'div' => false, 'class' => 'input-small', 'label' => false)) ?> </span>
            <span class="help-text">Optional</span>
        </div>
    </div>
</div>

<?php echo $this->Form->input('event_classification_id', array('empty' => 'Choose classification', 'required' => 'required', 'data-validate' => 'validate(required)'));
echo $this->Form->input('custom_url', array('label' => 'Event URL', 'class' => 'input-xlarge', 'placeholder' => 'If on external website'));
?>
<fieldset class="control-group">
    <label for="EventDescription" class="control-label">Description</label>
    <div class="controls">
        <?= $this->Form->input('description', array('label' => false, 'class' => 'span8', 'rows' => 12, 'div' => false)); ?>
        <p class="help-block">
            <?= $this->Element('markdown_basics'); ?>
        </p>
    </div>
</fieldset>
<?php
echo $this->Form->input('series_id', array('empty' => 'Choose the event series'));
echo $this->Form->input('map_id', array('empty' => 'Choose the event map'));

echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
$this->Form->unlockField('Event.lat');
echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng')));
$this->Form->unlockField('Event.lng');
?>

<?= $this->element('Events/edit_courses_organizers') ?>

<div class="control-group">
	<label class="control-label">Number of Participants</label>
    <div class="controls">
        <div>
            <?= $this->Form->input('number_of_participants', array('type' => 'number', 'min' => '0', 'max' => '4294967295', 'style' => 'width: 120px', 'div' => false, 'label' => false)) ?>
            <span class="help-text">(used for insurance purposes, overrides participant count from event results)</span>
        </div>
    </div>
</div>

<fieldset class="control-group">
    <label class="control-label">Meeting location</label>
    <div class="controls">
        <?= $this->Leaflet->draggableMarker('EventLat', 'EventLng', 10, array('div' => array('width' => '80%', 'height' => '400px'))); ?>
        <p class="help-block">Drag the marker to the meeting location</p>
    </div>
</fieldset>

<?= $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions'))) ?>
<script type="text/javascript">
// Fix for CakePHP form security - exclude the knockout inputs
$("#EventEditForm").submit(function() {
    $(this).find('[name ^= "ko_unique"]').attr("name", null);
});
</script>
