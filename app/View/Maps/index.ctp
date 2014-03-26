<div class="page-header">
    <div class="pull-right">
        <div class="btn-toolbar">
            <div class="btn-group"><a href="/maps/report" class="btn btn-info"><span class="glyphicon glyphicon-list"></span> Maps</a></div>
            <?php
            if($edit) {
                echo '<div class="btn-group"><a href="/maps/edit" class="btn btn-success"><span class="glyphicon-plus"></span> Map</a></div>';
            }
            ?>
        </div>
    </div>

    <h1>Maps</h1>
</div>
<div class="row">
    <div class="col-sm-4">
        <?= $this->ContentBlock->render('general_maps_information', null, '<hr class="divider" />') ?>
    </div>
    <div class="col-sm-8">
        <div class="multi-marker-map"
             data-lat="<?= Configure::read('Club.lat') ?>"
             data-lng="<?= Configure::read('Club.lng') ?>"
             data-zoom="10"
             data-fetch-url="<?= Configure::read('Rails.domain') ?>/api/maps.json"
             data-fetch-entity="maps"
             style="height: 400px; width: 100%">
        </div>
    </div>
</div>
