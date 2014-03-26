<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <button id="older-button" class="center-block btn btn-primary ladda-button" data-style="zoom-out">
            <span class="ladda-label">
                <span class="glyphicon glyphicon-chevron-up"></span>
                 Older
            </span>
        </button>
        <div class="event-list" data-event-list-type="time-window" data-event-list-older-button="#older-button" data-event-list-newer-button="#newer-button" data-event-list-url="<?= Configure::read('Rails.domain') ?>/club/<?= Configure::read('Club.id') ?>/events.xml?iof_version=3.0&external_significant_events=all">
            <h4 style="text-align: center" data-bind="visible: startTime">Showing events after <span data-bind="text: formattedStartTime"></span></h4>
            <?php
            $templateName = 'event-box-template';
            ?>
            <div data-bind="template: { name: '<?= $templateName ?>', foreach: events }"></div>
            <h4 style="text-align: center" data-bind="visible: endTime">Showing events before <span data-bind="text: formattedEndTime"></span></h4>
        </div>
        <button id="newer-button" class="center-block btn btn-primary ladda-button" data-style="zoom-out">
            <span class="ladda-label">
                <span class="glyphicon glyphicon-chevron-down"></span>
                 Newer
                </span>
            </span>
        </button>
        <?= $this->element('Events/knockout_box', compact('templateName')) ?>
    </div>
</div>
