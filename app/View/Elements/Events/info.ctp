<?php
$socialText = "Check out this event on";

if (!empty($event['Event']['facebook_url'])) {
?>
<a href="<?= $event['Event']['facebook_url'] ?>" class="btn btn-default social-event-button">
    <img src="/img/social/facebook-logo.png" srcset="/img/social/facebook-logo.png 1x, /img/social/facebook-logo@2x.png 2x" />
    <span class="social-event-text"><?= $socialText ?> Facebook</span>
</a>
<?php
}

if (!empty($event['Event']['attackpoint_url'])) {
?>
<a href="<?= $event['Event']['attackpoint_url'] ?>" class="btn btn-default social-event-button">
    <img src="/img/social/attackpoint-logo.png" srcset="/img/social/attackpoint-logo.png 1x, /img/social/attackpoint-logo@2x.png 2x" />
    <span class="social-event-text"><?= $socialText ?> Attackpoint</span>
</a>
<?php
}
?>

<?php if(!empty($event["Organizer"])) { ?>
<p><?= count($event["Organizer"]) > 1 ? '<b>Organizers</b>' : '<b>Organizer</b>' ?>: <?= $this->element('Organizers/list', array('organizers' => $event["Organizer"])); ?></p>
<?php }

if(!empty($event["Map"])) { 
    $map_id = $event["Map"]["id"];
    if($map_id != NULL) { ?>
        <p><b>Map</b>: <?= $this->Html->link($event["Map"]["name"],"/maps/view/$map_id")?></p>
    <?}?>
<?php }

if(!empty($event["Event"]["description"])) { 
    echo $event["Event"]["description"];
} else {
    echo "Check back soon for more information.";
} ?>
<hr class="divider" />

<?php if(!$event["Event"]["completed"] && !empty($event["Series"]["information"])) { ?>
    <h2 class="series-<?= $event['Series']['id'] ?>"><?= $event["Series"]["name"] ?></h2>
    <?= $event["Series"]["information"] ?>
    <hr class="divider" />
<?php } ?>
    
<?php if(!empty($event["Event"]["lat"])) { ?>
    <?php
    $query = $event["Event"]["lat"].','.$event["Event"]["lng"];
    $linkHTML = '<a class="btn btn-default" href="http://maps.google.com/?q='.$query.'">Google Maps</a>
    <a class="btn btn-default" href="http://maps.apple.com/?q='.$query.'">Apple Maps</a>';
    ?>
    <div>
        <div class="btn-group pull-right hidden-xs" style="margin-top: 5px;">
            <?= $linkHTML ?>
        </div>
        <h2>Location</h2>
    </div>
    <div class="visible-xs" style="margin-bottom: 12px;">
        <div class="btn-group">
            <?= $linkHTML ?>
        </div>
    </div>
    <?= $this->Leaflet->simpleMarker($event["Event"]["lat"], $event["Event"]["lng"], 14, '500px', array('pan-interaction' => false)); ?>
    <?php $this->append('secondaryScripts') ?>
    <script type="text/javascript">
        var clickHandler = function(e) {
            // Leaflet doesn't seem to provide a way to test for clicks on controls, so we have this hack here to ignore interactions with the zoom buttons
            var x = e.containerPoint.x;
            var y = e.containerPoint.y;
            if (x >= 15 && y >= 15 && x <= 34 && y <= 58) {
                return; // clicking the zoom buttons, we don't want to interfere..
            }
            var url =  '/events/map/<?= $event['Event']['id'] ?>';
            if ($(window).width() <= 768) {
                window.location = url;
            } else {
                $.fancybox.open({
                      width: '100%',
                      type: 'iframe',
                      href: url,
                });
            }
        };
        map.on('mousedown', clickHandler);
    </script>
    <?php $this->end(); ?> 
<?php } ?>

