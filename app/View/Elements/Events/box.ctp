<?php
$hiddenClassifications = array("Club", "Local");
$hasPicture = $this->Media->exists("Map", $mapId, "60x60");
?>
<div class="event-box <?php echo $hasPicture ? "has-picture" : ""; ?>">
    <a href="<?= $link ?>">
        <div class="event-box-inner">
            <?php if($hasPicture) { ?>
            <div class="event-box-icon hidden-sm">
                <?= $this->Media->image("Map", $mapId, "60x60") ?>
            </div>
            <?php } ?>
            <div class="event-box-left">
                <div class="location series-<?= $style ?>"><?= $location ?>
                <?php if(!empty($classification) && !in_array($classification, $hiddenClassifications)) { ?>
                    <span class="label label-info event-box-classification"><?= $classification ?></span>
                <?php } ?>
                </div>
                
                <div class="date date-text"><?= $date ?></div>
            </div>
        </div>
    </a>
</div>

