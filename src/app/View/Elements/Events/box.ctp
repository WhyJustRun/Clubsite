<?php
$hiddenClassifications = array("Club", "Local");
$hasPicture = $this->Media->exists("Map", $mapId, "60x60");
?>
<div class="event-box <?php echo $hasPicture ? "has-picture" : ""; ?>">
    <a href="<?php echo $link ?>">
        <div class="event-box-inner">
            <?php if($hasPicture) { ?>
            <div class="event-box-icon hidden-sm">
                <?php echo $this->Media->image("Map", $mapId, "60x60") ?>
            </div>
            <?php } ?>
            <div class="event-box-left">
                <div class="location series-<?php echo $style ?>">
                    <?php echo $location ?>
                </div>
                
                <div class="date date-text"><?php echo $date ?></div>
            </div>
        </div>
    </a>
</div>

