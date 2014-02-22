<div class="page-header">
    <h1><?= isset($map) ? "Edit" : "Add" ?> Map</h1>
</div>
<?= $this->Form->create('Map', array('class' => 'form-horizontal', 'action' => 'edit', 'enctype' => 'multipart/form-data')) ?>
<?php
echo $this->Form->input('name');
echo $this->Form->input('map_standard_id', array('empty' => 'Choose the standard'));
echo $this->Form->input('scale', array('label' => 'Scale', 'placeholder' => '10000'));
$useURLs = Configure::read('Club.use_map_urls');
if ($useURLs) {
    echo $this->Form->input('file_url', array('label' => 'Map File URL'));
} else {
    echo $this->Form->input('repository_path', array('label'=>'Repository Path', 'placeholder' => '/maps/vancouver/ubc/ubc.ocd'));
}
?>
<fieldset class="form-group">
    <label for="MapNotes" class="control-label col-sm-2">Notes</label>
    <div class="col-sm-10">
        <?= $this->Form->input('notes', array('label' => false, 'class' => 'form-control oa-wysiwyg', 'div' => false)); ?>
    </div>
</fieldset>
<fieldset class="form-group">
    <label class="control-label col-sm-2">Map Image
        <span data-toggle="tooltip" data-container="body" class="wjr-help-tooltip" title="" data-original-title="Provide a high quality image version of the orienteering map, JPG or GIF recommended. This is displayed on the map page so people who don't have OCAD can still view it.">
            <span class="glyphicon glyphicon-question-sign"></span>
        </span>
    </label>
    <div class="col-sm-10">
        <?= $this->Form->file('image', array('label' => 'Add image')) ?>
        <?php
        if(isset($map) && $this->Media->exists('Map', $map["Map"]["id"])) {
        echo "<br/>".$this->Media->image("Map", $map["Map"]["id"], '400x600', array('width' => '400px', 'class' => 'fitting-image'));
        } ?>
    </div>
</fieldset>
<?php
if(!empty($map)) {
    if($this->Media->exists('Map', $map["Map"]["id"])) { ?>
<fieldset class="form-group">
    <label class="control-label col-sm-2">Banner image</label>
    <div class="col-sm-10">
        <?= $this->Html->link('Generate', '/maps/generateBanner/'.$map["Map"]["id"], array('class' => 'btn btn-primary')) ?><br/><br/>
        <?= $this->Media->image("Map", $map["Map"]["id"], '60x60', array('width' => '60px')) ?>
    </div>
</fieldset>

<?
    }
}
echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng'))); 
$this->Form->unlockField('Map.lat');
$this->Form->unlockField('Map.lng');
?>
<fieldset class="form-group">
    <label class="control-label col-sm-2">Location</label>
    <div class="col-sm-10">
        <?= $this->Leaflet->draggableMarker('MapLat', 'MapLng', 10); ?>
        <p class="help-block">Drag the marker to the location of the map</p>
    </div>
</fieldset>
<?= $this->Form->end(array('label' => 'Save')) ?>


