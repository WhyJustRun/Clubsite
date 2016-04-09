<?php
if (empty($event['Event']['custom_url'])) {
?>
    <?php echo $this->element('Events/open_graph_tags', array('event' => $event)) ?>
    <?php echo $this->element('Events/header', array('event' => $event, 'canEdit' => $canEdit)) ?>

    <div class="row">
<?php
    if(!$event['Event']['has_results']) {
        // Show pre-event template
    ?>
        <div class="col-sm-8">
            <?php echo $this->element('Events/info', array('event' => $event)); ?>
        </div>

        <div class="col-sm-4">
            <?php echo $this->element('Events/registration_section', array('event' => $event, 'canEdit' => $canEdit)); ?>
        </div>
    <?php 
    } else {
        // Show post-event template
    ?>
        <div class="col-sm-6">
            <?php echo $this->element('Events/results_section', array('event' => $event, 'canEdit' => $canEdit)); ?> 
        </div>
        <div class="col-sm-6">
            <?php echo $this->element('Events/info', array('event' => $event)); ?>
        </div>
    <?php
    }
    ?>
    </div>

    <?php echo $this->element('Events/flickr_photos', array('eventId' => $event['Event']['id'])); ?>
<?php
} else {
    echo $this->element('Events/redirect_view', array('event' => $event, 'canEdit' => $canEdit));
}
?>
