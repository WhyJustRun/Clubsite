<?php
if(!empty($courses)) { ?>
	<h2>Course Maps</h2>
	<?php
	$mapsPosted = false;
	foreach ($courses as $course) {
		if($this->Media->exists("Course", $course["id"])) { 
			$mapsPosted = true;
			?>
			<div class="column">
				<h3><?= $course["name"] ?></h3><br>
				<?= $this->Media->linkedImage("Course", $course["id"], '100x150') ?> 
			</a>
		</div>
		<?php
    	}
    }
    if(!$mapsPosted) {
    	echo "No course maps posted yet.";
    }
}
?>
