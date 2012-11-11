<?php
$hiddenClassifications = array("Club", "Local");
?>
<div class="event-box">
    <a href="<?= $link ?>">
        <div class="event-box-inner">
            <?php if($this->Media->exists("Map", $mapId, "60x60")) { ?>
            <div class="event-box-icon visible-desktop">
                <?= $this->Media->image("Map", $mapId, "60x60") ?>
            </div>
            <?php } ?>
            <div class="event-box-left">
                <div class="location series-<?= $style ?>"><?= $location ?>
                <?php if(!empty($classification) && !in_array($classification, $hiddenClassifications)) { ?>
                    <span class="label label-info event-box-classification"><?= $classification ?></span>
                <?php } ?>
                </div>
                
                <div class="date"><?= $date ?></div>
            </div>
        </div>
    </a>
</div>
