<div class="event-box">
    <a href="<?= $link ?>">
        <div class="event-box-inner">
            <?php if($this->Media->exists("Map", $mapId, "60x60")) { ?>
            <div class="event-box-icon visible-desktop">
                <?= $this->Media->image("Map", $mapId, "60x60", array('width' => '45px')) ?>
            </div>
            <?php } ?>
            <div class="event-box-left">
                <div class="location series-<?= $style ?>"><?= $location ?></div>
                <div class="date"><?= $date ?></div>
            </div>
        </div>
    </a>
</div>
