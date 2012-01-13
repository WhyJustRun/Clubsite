<?php if(!empty($courses)) { ?>
<div class="column-box">
	<h2>Course Maps</h2>
    <?foreach ($courses as $course) {
        $counter = 0;
        if($this->Media->exists("Course", $course["id"])) {?>
            <div class="column">
                <h3><?=$course["name"]?></h3><br>
                <?= $this->Media->linkedImage("Course", $course["id"], '100x150') ?> 
                </a>
            </div>
        <? $counter++;
        }
    }
    if($counter == 0) {
        echo "No course maps posted yet.";
    }?>

</div>
<?}?>
