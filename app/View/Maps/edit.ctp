<div class="page-header">
    <h1><?php echo isset($map) ? "Edit" : "Add" ?> Map</h1>
</div>
<?php
echo $this->Form->create('Map', array('class' => 'form-horizontal', 'url' => 'edit', 'enctype' => 'multipart/form-data'));
echo $this->Form->input('name');
echo $this->Form->input('map_standard_id', array('empty' => 'Choose the standard'));
echo $this->Form->input('scale', array('label' => 'Scale', 'placeholder' => '10000'));
echo $this->Form->input('file_url', array('label' => 'Map File URL'));
?>
<fieldset class="form-group">
    <label for="MapNotes" class="control-label col-sm-2">Notes</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('notes', array('label' => false, 'class' => 'form-control wjr-wysiwyg', 'div' => false)); ?>
    </div>
</fieldset>
<fieldset class="form-group">
    <label class="control-label col-sm-2">Map Image
        <span data-toggle="tooltip" data-container="body" class="wjr-help-tooltip" title="" data-original-title="Provide a high quality image version of the orienteering map, JPG or GIF recommended. This is displayed on the map page so people who don't have OCAD can still view it.">
            <span class="glyphicon glyphicon-question-sign"></span>
        </span>
    </label>
    <div class="col-sm-10">
        <?php echo $this->Form->file('image', array('label' => 'Add image')) ?>
        <?php
        if (isset($map) && $this->Media->exists('Map', $map["Map"]["id"])) {
        echo "<br/>".$this->Media->image("Map", $map["Map"]["id"], '400x600', array('width' => '400px', 'class' => 'fitting-image'));
        } ?>
    </div>
</fieldset>
<?php if (!empty($map) && $this->Media->exists('Map', $map["Map"]["id"])) { ?>
<fieldset class="form-group">
    <label class="control-label col-sm-2">Banner image</label>
    <div class="col-sm-10">
        <?php echo $this->Html->link('Generate', '/maps/generateBanner/'.$map["Map"]["id"], array('class' => 'btn btn-primary')) ?><br/><br/>
        <?php echo $this->Media->image("Map", $map["Map"]["id"], '60x60', array('width' => '60px')) ?>
    </div>
</fieldset>
<?php } ?>
<fieldset class="form-group">
    <label class="control-label col-sm-2">Location</label>
    <div class="col-sm-10">
        <?php
        echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
        echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng')));

        $this->Form->unlockField('Map.lat');
        $this->Form->unlockField('Map.lng');
        ?>
        <div class="draggable-marker-map"
             data-lat-element="#MapLat"
             data-lng-element="#MapLng"
             data-zoom="10"
             style="height: 400px; width: 100%">
        </div>

        <p class="help-block">Drag the marker to the location of the map</p>
    </div>
</fieldset>
<?php echo $this->Form->end(array('label' => 'Save')) ?>
