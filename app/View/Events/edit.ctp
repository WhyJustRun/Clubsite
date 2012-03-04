<h1><?= $type?> Event</h1>

<?php 
echo $this->Form->create('Event', array('action' => 'edit'));
// Hidden JSON encoded organizer data from the edit organizers UI
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->hidden('organizers', array('value' => $this->data["Event"]["organizers"], 'data-bind' => 'value: ko.toJSON(organizers)'));
echo $this->Form->hidden('courses', array('value' => $this->data["Event"]["courses"], 'data-bind' => 'value: ko.toJSON(courses)'));
?>
<div class='span-10'>
	<div class='column-box'>
	<?
	   echo $this->Form->input('name');
	   // TODO: This should be a javascript datetime picker (though there are few options)
	   echo $this->Form->input('date', array('type' => 'text'));
       echo $this->Form->input('event_classification_id', array('empty' => 'Choose classification'));
	?>
	<?
	   echo $this->Form->input('description', array('style' => 'height: 300px'));
	   echo $this->Element('markdown_basics');
	?>
	</div>
	<script type="text/javascript">
	$(function() {
		$('#EventDate').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'hh:mm:ss',
		});
	});
	</script>
	<div class='column-box'>
	<?
	   echo $this->Form->input('series_id', array('empty' => 'Choose the event series'));
	   echo $this->Form->input('map_id', array('empty' => 'Choose the event map'));
	   //echo $this->Form->input('is_ranked', array('type' => 'checkbox'));
	?>
	<?
	   echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
	   echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng')));
	?>
	   
	</div>
	<div class='padded'>
	<?php
	echo $this->Form->end('Save');
	?>
	</div>
	
</div>

<div class='span-13 last column-box'>
<?php echo $this->element('Events/edit_courses_organizers', array('div' => 'class="column-box"')); ?>
<br/>
<div class="input">
      <label class="required">Meeting location: Drag the marker below to the location</label>
      <br/>
      <?php
         echo $this->Leaflet->draggableMarker('EventLat', 'EventLng', 10);
      ?>
</div>
</div>
