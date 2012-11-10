<div class="page-header">
    <h1><?= isset($map) ? "Edit" : "Add" ?> Map</h1>
</div>
<?= $this->Form->create('Map', array('class' => 'form-horizontal', 'action' => 'edit', 'enctype' => 'multipart/form-data')) ?>
<?
echo $this->Form->input('name');
echo $this->Form->input('map_standard_id', array('empty' => 'Choose the standard'));
echo $this->Form->input('repository_path', array('label'=>'Repository Path', 'placeholder' => '/maps/vancouver/ubc/ubc.ocd'));
echo $this->Form->input('scale', array('label' => 'Scale', 'placeholder' => '10000'));

if(!empty($map)) { ?>
        <fieldset class="control-group">
            <label class="control-label">Image</label>
            <div class="controls">
                <?= $this->Form->file('image', array('label' => 'Add image')) ?>
                <?php
                if($this->Media->exists('Map', $map["Map"]["id"])) {
                    echo "<br/>".$this->Media->image("Map", $map["Map"]["id"], '400x600');
                 } ?>
            </div>
        </fieldset>
    <?php
    if($this->Media->exists('Map', $map["Map"]["id"])) { ?>
        <fieldset class="control-group">
            <label class="control-label">Banner image</label>
            <div class="controls">
                <?= $this->Html->link('Generate', '/maps/generateBanner/'.$map["Map"]["id"], array('class' => 'btn btn-primary')) ?><br/><br/>
                <?= $this->Media->image("Map", $map["Map"]["id"], '60x60') ?>
            </div>
        </fieldset>
        
    <? }
}
echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng'))); 
$this->Form->unlockField('Map.lat');
$this->Form->unlockField('Map.lng');
?>
<fieldset class="control-group">
    <label class="control-label">Location</label>
    <div class="controls">
        <?= $this->Leaflet->draggableMarker('MapLat', 'MapLng', 10); ?>
        <p class="help-block">Drag the marker to the location of the map</p>
    </div>
</fieldset>
<?= $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions'))) ?>

