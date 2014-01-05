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
<div class="form-group">
    <label class="control-label col-sm-2">Start Time</label>
    <div class="col-sm-10">
        <div class="form-inline">
            <div class="form-group">
                <?= $this->Form->text('date', array('value' => $date, 'size' => '10', 'maxLength' => 10, 'data-validate' => 'validate(required)', 'placeholder' => 'yyyy-mm-dd', 'class' => 'form-control date-picker', 'data-date-format' => 'yyyy-mm-dd', 'div' => false, 'label' => false)) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->text('time', array('placeholder' => 'hh:mm:ss', 'size' => '8', 'data-validate' => 'validate(required)', 'maxLength' => 8, 'value' => $time, 'div' => false, 'class' => 'form-control', 'label' => false)) ?>
            </div>
             (24 hour format)
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2">End Time</label>
    <div class="col-sm-10">
        <div class="form-inline">
            <div class="form-group">
                <?= $this->Form->text('finish_date', array('size' => '10', 'value' => $finishDate, 'maxLength' => 10, 'placeholder' => 'yyyy-mm-dd', 'class' => 'form-control date-picker', 'data-date-format' => 'yyyy-mm-dd', 'div' => false, 'label' => false)) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->text('finish_time', array('size' => '8', 'placeholder' => 'hh:mm:ss', 'maxLength' => 8, 'value' => $finishTime, 'div' => false, 'class' => 'form-control', 'label' => false)) ?>
            </div>
             (optional)
        </div>
    </div>
</div>

<?php echo $this->Form->input('event_classification_id', array('empty' => 'Choose classification', 'required' => 'required', 'data-validate' => 'validate(required)'));
?>

<?php
$urlFieldOptions = array('size' => '80', 'label' => false, 'div' => false, 'class' => 'form-control', 'data-validate' => 'validate(url_or_empty)');
?>
<div class="form-group">
    <label class="control-label col-sm-2">Redirect URL
            <span data-toggle="tooltip" data-container="body" class="wjr-help-tooltip" title="If you want to use a third party website for the event, enter the URL for the event page here. NOTE: People accessing your event may be automatically redirected to the other website, depending on where on WhyJustRun they come from.">
                <span class="glyphicon glyphicon-question-sign"></span>
            </span>
    </label>
    <div class="col-sm-10">
        <?= $this->Form->input('custom_url', array_merge($urlFieldOptions, array('placeholder' => 'Only use if all info is on external website'))) ?>
    </div>
</div>


<div class="form-group">
    <label class="control-label col-sm-2">Registration URL
        <span data-toggle="tooltip" data-container="body" class="wjr-help-tooltip" title="If you want to use a third party registration system like Zone4, enter the URL to the registration page here.">
            <span class="glyphicon glyphicon-question-sign"></span>
        </span>
    </label>
    <div class="col-sm-10">
        <?= $this->Form->input('registration_url', $urlFieldOptions) ?>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">Results URL
        <span data-toggle="tooltip" data-container="body" class="wjr-help-tooltip" title="If you post results on a third party website like WinSplits, enter the URL to the results page here after the event.">
            <span class="glyphicon glyphicon-question-sign"></span>
        </span>
    </label>
    <div class="col-sm-10">
        <?= $this->Form->input('results_url', $urlFieldOptions) ?>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">RouteGadget URL
        <span data-toggle="tooltip" data-container="body" class="wjr-help-tooltip" title="If you have posted a RouteGadget for the event, enter the URL to the RouteGadget page here after the event.">
            <span class="glyphicon glyphicon-question-sign"></span>
        </span>
    </label>
    <div class="col-sm-10">
        <?= $this->Form->input('routegadget_url', $urlFieldOptions) ?>
    </div>
</div>

<fieldset class="form-group">
    <label for="EventDescription" class="control-label col-sm-2">Description</label>
    <div class="col-sm-10">
        <?= $this->Form->input('description', array('label' => false, 'class' => 'form-control oa-wysiwyg', 'rows' => 12, 'div' => false)); ?>
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

<div class="form-group">
    <label class="control-label col-sm-2">Number of Participants</label>
    <div class="col-sm-10">
        <div class="form-inline">
            <div class="form-group">
                <?= $this->Form->input('number_of_participants', array('type' => 'number', 'min' => '0', 'max' => '4294967295', 'class' => 'form-control', 'div' => false, 'label' => false)) ?>
            </div>
            (Optional: used for insurance purposes, overrides participant count from event results)
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-2">Ranked</label>
    <div class="col-sm-10">
        <div class="checkbox">
            <?=$this->Form->input('is_ranked', array('type'=>'checkbox', 'div' => false, 'label' => false, 'default'=> "1")); ?>
            (should results be included in rankings?)
        </div>
    </div>
</div>

<fieldset class="form-group">
    <label class="control-label col-sm-2">Meeting location</label>
    <div class="col-sm-10">
        <?= $this->Leaflet->draggableMarker('EventLat', 'EventLng', 10, array('div' => array('width' => '100%', 'height' => '400px'))); ?>
        <p class="help-block">Drag the marker to the meeting location</p>
    </div>
</fieldset>

<div class="form-group">
    <?= $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'col-sm-offset-2 col-sm-10'))) ?>
</div>
<?php
$this->append('secondaryScripts');
?>
<script type="text/javascript">
// Fix for CakePHP form security - exclude the knockout inputs
$("#EventEditForm").submit(function() {
    $(this).find('[name ^= "ko_unique"]').attr("name", null);
});
</script>
<?php $this->end(); ?>
