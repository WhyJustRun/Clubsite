<?php
$socialText = "Check out this event on";

if (!empty($event['Event']['facebook_url'])) {
?>
<a href="<?= $event['Event']['facebook_url'] ?>" class="btn btn-default social-event-button">
    <img width="20px" src="/img/social/facebook-logo.png" srcset="/img/social/facebook-logo.png 1x, /img/social/facebook-logo@2x.png 2x" />
    <span class="social-event-text"><?= $socialText ?> Facebook</span>
</a>
<?php
}

if (!empty($event['Event']['attackpoint_url'])) {
?>
<a href="<?= $event['Event']['attackpoint_url'] ?>" class="btn btn-default social-event-button">
    <img width="20px" src="/img/social/attackpoint-logo.png" srcset="/img/social/attackpoint-logo.png 1x, /img/social/attackpoint-logo@2x.png 2x" />
    <span class="social-event-text"><?= $socialText ?> Attackpoint</span>
</a>
<?php
}

if (!empty($event['Event']['attackpoint_url']) || !empty($event['Event']['facebook_url'])) {
?>
<br/>
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
    <iframe width="100%" height="400" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=<?= $event["Event"]["lat"] ?>%2C<?= $event["Event"]["lng"] ?>&key=<?= Configure::read("GoogleMaps.apiKey") ?>"></iframe>
<?php } ?>
