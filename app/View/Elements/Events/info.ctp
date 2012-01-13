<?php if(!$event["Event"]["completed"] && !empty($event["Series"]["information"])) { ?>
<div class="column-box">
	<h2><?= $event["Series"]["name"] ?> Series Info</h2>
    <?= $this->Markdown->render($event["Series"]["information"])?>
</div>
<?php } ?>

<div class="column-box">
	<h2>Event Information</h2>
	<?php if(!empty($event["Organizer"])) { ?>
	<p>Organizer(s): <?= $this->element('Organizers/list', array('organizers' => $event["Organizer"])); ?></p>
	<?php }
	if(!empty($event["Event"]["description"])) { 
		// TODO-RWP Should sanitize imported data instead
		echo $this->Markdown->render($event["Event"]["description"]);
	} else {
		echo "Check back soon for more information.";
	} ?>
</div>

<?php if(!empty($event["Event"]["lat"])) { ?>
<div class="column-box">
	<h2>Location</h2>
	<?php 
	echo $this->Leaflet->simpleMarker($event["Event"]["lat"], $event["Event"]["lng"], 14, '500px');
	?>
</div>
<?php } ?>
