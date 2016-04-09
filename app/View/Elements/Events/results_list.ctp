<?php
// Params: $eventId
?>
<div class="results-list">
    <div id="list" class="result-list" data-result-list-url="<?php echo Configure::read('Rails.domain') ?>/iof/3.0/events/<?php echo $eventId ?>/result_list.xml">
        <?php echo $this->element('Events/knockout_result_list', array('mode' => 'normal')) ?>
    </div>
</div>
