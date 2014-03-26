<?php
// Params: $eventId
?>
<div class="results-list">
    <div id="list" class="result-list" data-result-list-url="<?= Configure::read('Rails.domain') ?>/iof/3.0/events/<?= $eventId ?>/result_list.xml">
        <?= $this->element('Events/knockout_result_list', array('mode' => 'normal')) ?>
    </div>
</div>
