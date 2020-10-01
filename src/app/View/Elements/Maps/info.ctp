<?php if(!empty($map["Map"]["lat"])) { ?>
    <h3>Location</h3>
    <iframe width="100%" height="500" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=<?php echo $map["Map"]["lat"] ?>%2C<?php echo $map["Map"]["lng"] ?>&key=<?php echo Configure::read("GoogleMaps.apiKey") ?>"></iframe>
<?php } ?>
