<div class="page-header">
    <div class="pull-right">
        <?= $this->element('Events/add_link') ?>
        <a href="/events/index" class="btn btn-primary">
            <span class="glyphicon glyphicon-calendar"></span>
             Calendar
        </a>
    </div>
    <h1>Events</h1>
</div>
<?= $this->element('Events/list'); ?>
</div>
