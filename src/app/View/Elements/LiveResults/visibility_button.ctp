<?php
// Params: $event
?>
<a class="btn btn-primary" href="/events/toggle_live_results_visibility/<?php echo $event['Event']['id'] ?>/<?php echo $event['ResultList']['visible'] ? "false" : "true" ?>/">
    <?php echo $event['ResultList']['visible'] ? 'Hide Live Results' : 'Show Live Results' ?>
</a>
<br/><br/>
