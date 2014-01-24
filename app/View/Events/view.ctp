<?php
if (empty($event['Event']['custom_url'])) {
?>
    <?= $this->element('Events/open_graph_tags', array('event' => $event)) ?>
    <?= $this->element('Events/header', array('event' => $event, 'canEdit' => $canEdit)) ?>

    <div class="row">
    <?php
    if(!$event["Event"]["results_posted"] &&
       !$event['Event']['results_url'] &&
       !$event['Event']['routegadget_url']) {
        // Show pre-event template
    ?>
        <div class="col-sm-8">
            <?= $this->element('Events/info', array('event' => $event)); ?>
        </div>

        <div class="col-sm-4">
            <?= $this->element('Events/registration_section', array('event' => $event)); ?>
        </div>
    <?php 
    } else {
        // Show post-event template
    ?>
        <div class="col-sm-6">
            <?= $this->element('Events/results_section', array('event' => $event)); ?> 
        </div>
        <div class="col-sm-6">
            <?= $this->element('Events/info', array('event' => $event)); ?>
        </div>
    <?php
    }
    ?>
    </div>

    <?= $this->element('Events/flickr_photos', array('eventId' => $event['Event']['id'])); ?>
<?php
} else {
    echo $this->element('Events/redirect_view', array('event' => $event, 'canEdit' => $canEdit));
}
?>
