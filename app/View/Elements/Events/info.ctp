<?php if(!$event["Event"]["completed"] && !empty($event["Series"]["information"])) { ?>
	<div class="column-box">
		<h2><?= $event["Series"]["name"] ?> Series Info</h2>
		<?= $this->Markdown->render($event["Series"]["information"]) ?>
	</div>
<?php } ?>

<div class="column-box">
	<h2>Event Information</h2>
	<?php if(!empty($event["Organizer"])) { ?>
    <p><?= count($event["Organizer"]) > 1 ? '<b>Organizers</b>' : '<b>Organizer</b>' ?>: <?= $this->element('Organizers/list', array('organizers' => $event["Organizer"])); ?></p>
	<?php }

    if(!empty($event["Map"])) { 
        $map_id = $event["Map"]["id"];?>
        <p><b>Map</b>: <?= $this->Html->link($event["Map"]["name"],"/maps/view/$map_id")?></p>
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
		<?= $this->Leaflet->simpleMarker($event["Event"]["lat"], $event["Event"]["lng"], 14, '500px'); ?>
	</div>
<?php } ?>
