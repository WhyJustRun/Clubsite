<?php if(!empty($map["Map"]["lat"])) { ?>
    <h3>Location</h3>
    <div class="simple-marker-map"
         data-lat="<?= $map["Map"]["lat"] ?>"
         data-lng="<?= $map["Map"]["lng"] ?>"
         data-zoom="14"
         style="height: 500px; width: 100%">
    </div>
<?php } ?>
