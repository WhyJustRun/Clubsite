<div class="event-box">
    <a href="<?= $link ?>">
        <div class="event-box-inner">
            <div class="event-box-icon"><?=$this->Media->image("Map", $mapId, "60x60")?></div>
            <div class="event-box-left">
                <div class="location series-<?= $style ?>"><?= $location ?></div>
                <!--div class=""><?= $location ?></divi-->
                <div class="city"><?= $city ?></div>
                <div class="date"><?= $date ?></div>
            </div>
        </div>
    </a>
</div>
