<?php
// Param: event - the event which live results are being displayed for
?>
<p>
    <b>Live results - these are not finalized!</b>
    <br/>
    Results will automatically update, you don't need to refresh your browser.
</p>

<div class="results-list">
    <div class="result-list" data-result-list-url="<?php echo Configure::read('Rails.domain') ?>/iof/3.0/events/<?php echo $event['Event']['id'] ?>/live_result_list.xml" data-result-list-mode="live">
        <?php echo $this->element('Events/knockout_result_list', array('mode' => 'live')) ?>
    </div>
</div>
