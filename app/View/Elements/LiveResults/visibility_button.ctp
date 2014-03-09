<?php
// Params: $event
?>
<a class="btn btn-primary" href="/events/toggle_live_results_visibility/<?= $event['Event']['id'] ?>/<?= $event['LiveResult']['visible'] ? "false" : "true" ?>/">
    <?= $event['LiveResult']['visible'] ? 'Hide Live Results' : 'Show Live Results' ?>
</a>
<br/><br/>
