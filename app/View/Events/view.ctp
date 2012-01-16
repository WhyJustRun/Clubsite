<div class="right">
<?=$this->Html->link('XML', '/events/view/'.$event["Event"]["id"].'.xml', array('class' => 'button'))?>
<?php
if($edit) {
	echo $this->Html->link('Edit', '/events/edit/'.$event["Event"]["id"], array('class' => 'button'));
	echo "<br/>";
	echo $this->Html->link('Post Results','/events/editResults/'.$event["Event"]["id"], array('class' => 'button'));
	echo "<br/>";
	//echo $this->Html->link('Upload Results','/events/uploadResults/'.$event["Event"]["id"], array('class' => 'button'));
	//echo "<br/>";
	echo $this->Html->link('Upload Maps','/events/uploadMaps/'.$event["Event"]["id"], array('class' => 'button'));
    echo "<br/>";
	echo $this->Html->link('Printable entries','/events/printableEntries/'.$event["Event"]["id"], array('class' => 'button'));
	// Get everything to line up nicely
	echo "<br/><br/>";
} else {
    echo "<br/><br/><br/><br/>";
}
?>
</div>
<header>
	<h1 class="series-<?= $event["Series"]["id"]; ?> event-header"><?= $event["Event"]["name"]; ?></h1>
	<h2 class="series-<?= $event["Series"]["id"]; ?> event-header"><?= $event["Series"]["name"] ?></h2>
	<h3 class="event-header"><?php $date = new DateTime($event["Event"]["date"]); echo $date->format("F jS Y g:ia"); ?></h3>
</header>

<?php if($event["Event"]["results_posted"] === '0' ) { 
// Show event information
?>
	<div class="column span-16">
		<?php echo $this->element('Events/info', array('event' => $event)); ?>
	</div>
	
	<?php if($event["Event"]["completed"] === true) { ?>
	<div class="results column span-8 last">
        <?php echo $this->element('Courses/course_maps', array('courses' => $event["Course"])); ?>
	</div>
	<?php } elseif(count($event["Course"]) === 0) { ?>
		<div class="results column span-8 last">
			<header>
				<h2>Course Registration</h2>
			</header>
			<p>No courses have been posted yet.</p>
		</div>
 	<?php } else { ?>
	<div class="results column span-8 last">
		<header>
			<h2>Course Registration</h2>
		</header>
		<div class="courses">
			<?php foreach($event["Course"] as $course) { ?>
			<div class="course column-box">
				<div class="course-info">
					<div>
					<div class="span-4">
						<h3><?= $course["name"] ?></h3>
					</div>
					<div class="right">
						<?php if($course["registered"] === false) { ?>
						<?php echo $this->Form->create('Course', array('url' => '/Courses/register/'.$course["id"])); ?>
						<?php echo $this->Form->end("Register"); 
						 } else { ?>
						<form method="post" action="/Courses/unregister/<?= $course["id"] ?>" accept-charset="utf-8">
							<div style="display:none;">
								<input type="hidden" name="_method" value="POST" />
							</div>
							<div class="unsubmit">
								<input type="submit" value="Unregister" />
							</div>
						</form>
						<?php } ?>
					</div>
					<br style="clear"/>
					</div>
					<div>
					<?= $course["description"] ?>
					<p>
					<?php
					if(!empty($course["distance"])) {
						echo "<br/><strong>Distance</strong>: ${course['distance']}m";
					}
					if(!empty($course["climb"])) {
						echo "<br/><strong>Climb</strong>: ${course['climb']}m";
					}
					?>
					</p>
					</div>
				</div>
				<div class="results-list">
					<?php echo $this->element('Results/list', array('type' => 'registrations', 'results' => $course["Result"])); ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
<?php } else {
// Show results page
?>
<div class="column span-12">
	<div class="results padded">
		<header>
			<h2>Results</h2>
		</header>
        <?php echo $this->element('Events/files', array('id' => $event["Event"]["id"])); ?>
		<div class="results-list">
		<?php foreach($event["Course"] as $course) { ?>
			<h3><?= $course["name"] ?></h3>
			<?php echo $this->element('Results/list', array('type' => 'results', 'results' => $course["Result"])); ?>
		<?php } ?>
        <?php echo $this->element('Courses/course_maps', array('courses' => $event["Course"])); ?>
		</div>
	</div>
</div>
<div class="column span-12 last">
	<?php echo $this->element('Events/info', array('event' => $event)); ?>
</div>
<?php } ?>
