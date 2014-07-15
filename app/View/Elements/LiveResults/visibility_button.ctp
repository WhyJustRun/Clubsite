<?php
// Params: $event
?>
<a class="btn btn-primary" href="/events/toggle_live_results_visibility/<?= $event['Event']['id'] ?>/<?= $event['ResultList']['visible'] ? "false" : "true" ?>/">
    <?= $event['ResultList']['visible'] ? 'Hide Live Results' : 'Show Live Results' ?>
</a>
<br/><br/>
