<?php
// Params: type (either list or calendar)
?>
<div class="page-header">
    <div class="pull-right">
        <?= $this->element('Events/add_link') ?>
        <?php
        if ($this->User->hasEventPlannerAccess()) {
        ?>
        <a href="/events/planner" class="btn btn-default">
            <span class="glyphicon glyphicon-briefcase"></span>
            Event Planner
        </a>
        <?php
        }

        if ($type === 'calendar') {
        ?>
        <a href="/events/listing" class="btn btn-primary">
            <span class="glyphicon glyphicon-list"></span>
             List
        </a>
        <?php
        } else if ($type === 'list') {
        ?>
        <a href="/events/index" class="btn btn-primary">
            <span class="glyphicon glyphicon-calendar"></span>
             Calendar
        </a>
        <?php
        }
        ?>
    </div>
    <h1>Events</h1>
</div>
