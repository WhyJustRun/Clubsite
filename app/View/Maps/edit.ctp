<h1><? if(isset($map)) echo "Edit"; else echo "Add";?> Map</h1>
<?=$this->Form->create('Map', array('action' => 'edit', 'enctype' => 'multipart/form-data'))?>
<div class='span-12'>
    <div class='column-box'>
    <?
    echo $this->Form->input('name');
    echo $this->Form->input('map_standard_id', array('empty' => 'Choose the map standard of this map'));
    echo $this->Form->input('created', array('dateFormat'=>'DMY', 'timeFormat'=>'NONE', 'minYear'=>1950));
    echo $this->Form->input('modified', array('dateFormat'=>'DMY', 'timeFormat'=>'NONE', 'minYear'=>1950));
    echo $this->Form->input('repository_path', array('label'=>'Repository path: e.g. /maps/vancouver/ubc/ubc.ocd'));
    echo $this->Form->input('scale', array('label'=>'Scale (e.g. 10000 means 1:10,000)'));
    // Map image
    echo "<div class=\"input\"><label>Add image</label></div>";
    echo $this->Form->file('image');
    if(!empty($map)) {
        // Move into helper
       if($this->Media->exists('Map', $map["Map"]["id"])) { ?>
            <div class="input"><label>Current image</label></div>
            <?= $this->Media->image("Map", $map["Map"]["id"], '400x600') ?>
<?     }
       if($this->Media->exists('Map', $map["Map"]["id"], '60x60')) { ?>
            <div class="input"><label>Current banner image</label></div>
            <?= $this->Media->image("Map", $map["Map"]["id"], '60x60') ?>
        <?     }?>
            <?=$this->Html->link('Generate new', '/maps/generateBanner/'.$map["Map"]["id"], array('class' => 'button'))?>

    
        <?}
    echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
    echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng'))); ?>
    </div>
</div>
<div class='span-12 last'>
    <div class='column-box'>
        <div class="input">
            <label class="required">Drag the marker to the central location of the map</label>
            <?= $this->Leaflet->draggableMarker('MapLat', 'MapLng', 10)?>
        </div>
    </div>
</div>

<?php
echo $this->Form->end('Save');?>

