<?php
// Params: $eventId
?>
<div class="results-list">
    <?php $this->Html->script('result_viewer', array('block' => 'secondaryScripts')); ?>
    <div id="list" class="result-list" data-result-list-url="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $eventId ?>/result_list.xml">
        <?= $this->element('Events/knockout_result_list', array('mode' => 'normal')) ?>
    </div>
</div>
