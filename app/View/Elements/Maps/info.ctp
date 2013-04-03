<?php if(!empty($map["Map"]["lat"])) { ?>
    <h3>Location</h3>    
    <?= $this->Leaflet->simpleMarker($map["Map"]["lat"], $map["Map"]["lng"], 14, '500px'); ?>
<?php } ?>

