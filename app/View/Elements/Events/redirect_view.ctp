<?php
// Params: canEdit, event
if (!$canEdit) {
    // This should be handled by the controller (redirect the user to the redirect url)
    throw new Exception("Event redirect not performed.");
}

echo $this->element('Events/header', array('event' => $event, 'canEdit' => $canEdit));
?>

<div class="alert alert-danger">
    <span class="label label-danger">IMPORTANT</span>
    <p>You are seeing this page because you can edit this event. Normal users will be redirected automatically to the external event page. To start showing this WhyJustRun event page to normal users, remove the Redirect URL on the edit event page.</p>
</div>
<br/>
<p>
    <a href="<?= $event['Event']['custom_url'] ?>" class="btn btn-success btn-lg">Continue to Event</a>
    <a href="/events/edit/<?= $event['Event']['id'] ?>" class="btn btn-primary btn-lg">Edit Event</a>
</p>
