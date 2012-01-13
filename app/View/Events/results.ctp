<header>
	<h1 class="series-<?= $event["Series"]["id"]; ?> event-header"><?= $event["Event"]["name"]; ?></h1>
	<h2 class="series-<?= $event["Series"]["id"]; ?> event-header"><?= $event["Series"]["name"] ?></h2>
	<h3 class="event-header"><?php $date = new DateTime($event["Event"]["date"]); echo $date->format("F jS Y g:ia"); ?></h3>
</header>

<?php if($event["Event"]["results_posted"] === '0' ) { ?>
<div class="column-box">
    <h3>Not results posted yet</h3>
</div>

<?php } else { ?>
<div class="results padded">
    <div class="results-list">
    <?php foreach($event["Course"] as $course) { ?>
        <h3><?= $course["name"] ?></h3>
        <?php echo $this->element('Results/list', array('type' => 'results', 'results' => $course["Result"])); ?>
    <?php } ?>
    </div>
</div>
<?php } ?>
