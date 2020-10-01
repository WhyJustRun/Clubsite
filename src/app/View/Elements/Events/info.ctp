<?php
$socialText = "Check out this event on";

if (!empty($event['Event']['facebook_url'])) {
?>
<a href="<?php echo $event['Event']['facebook_url'] ?>" class="btn btn-default social-event-button">
    <img width="20px" src="/img/social/facebook-logo.png" srcset="/img/social/facebook-logo.png 1x, /img/social/facebook-logo@2x.png 2x" />
    <span class="social-event-text"><?php echo $socialText ?> Facebook</span>
</a>
<?php
}

if (!empty($event['Event']['attackpoint_url'])) {
?>
<a href="<?php echo $event['Event']['attackpoint_url'] ?>" class="btn btn-default social-event-button">
    <img width="20px" src="/img/social/attackpoint-logo.png" srcset="/img/social/attackpoint-logo.png 1x, /img/social/attackpoint-logo@2x.png 2x" />
    <span class="social-event-text"><?php echo $socialText ?> Attackpoint</span>
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
<p><?php echo count($event["Organizer"]) > 1 ? '<b>Organizers</b>' : '<b>Organizer</b>' ?>: <?php echo $this->element('Organizers/list', array('organizers' => $event["Organizer"])); ?></p>
<?php }

if(!empty($event["Map"])) {
    $map_id = $event["Map"]["id"];
    if($map_id != NULL) { ?>
        <p><b>Map</b>: <?php echo $this->Html->link($event["Map"]["name"],"/maps/view/$map_id")?></p>
    <?php }?>
<?php }

if(!empty($event["Event"]["description"])) {
    echo $event["Event"]["description"];
} else {
    echo "Check back soon for more information.";
} ?>
<hr class="divider" />

<?php if(!$event["Event"]["completed"] && !empty($event["Series"]["information"])) { ?>
    <h2 class="series-<?php echo $event['Series']['id'] ?>"><?php echo $event["Series"]["name"] ?></h2>
    <?php echo $event["Series"]["information"] ?>
    <hr class="divider" />
<?php } ?>

<?php if(!empty($event["Event"]["lat"])) { ?>
    <h2>Location</h2>
    <iframe width="100%" height="400" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=<?php echo $event["Event"]["lat"] ?>%2C<?php echo $event["Event"]["lng"] ?>&key=<?php echo Configure::read("GoogleMaps.apiKey") ?>"></iframe>
<?php } ?>
