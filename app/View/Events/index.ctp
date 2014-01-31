<div class="page-header">
    <div class="pull-right">
        <?= $this->element('Events/add_link') ?>
        <a href="/events/listing" class="btn btn-primary">
            <span class="glyphicon glyphicon-list"></span>
             List
        </a>
    </div>
    <h1>Events</h1>
</div>
<?= $this->element('Events/calendar', compact($day, $month, $year)); ?>
</div>
