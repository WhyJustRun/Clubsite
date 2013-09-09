<header class="page-header">
    <h1>Edit Club Information</h1>
</header>
<?= $this->Form->create('Club', array('class' => 'form-horizontal', 'data-validate' => 'ketchup', 'action' => 'edit')); ?>
<div class="control-group">
    <label class="control-label">Name</label>
    <div class="controls">
        <?= $this->Form->input('name', array('div' => false, 'label' => false, 'class' => 'input-xlarge', 'data-validate' => 'validate(required)', 'required' => 'required')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Acronym</label>
    <div class="controls">
        <?= $this->Form->input('acronym', array('div' => false, 'label' => false, 'data-validate' => 'validate(required)', 'required' => 'required')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Location</label>
    <div class="controls">
        <?= $this->Form->input('location', array('div' => false, 'label' => false, 'data-validate' => 'validate(required)', 'required' => 'required')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Description</label>
    <div class="controls">
        <?= $this->Form->input('description', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'input-xxlarge')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Website URL</label>
    <div class="controls">
        <?= $this->Form->input('url', array('div' => false, 'label' => false, 'class' => 'input-xlarge', 'data-validate' => 'validate(required)', 'required' => 'required')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Facebook Page URL</label>
    <div class="controls">
        <?= $this->Form->input('facebook_page_id', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'input-xlarge')) ?> Used on the home page for the news section
    </div>
</div>
<div class="control-group">
    <label class="control-label">Page Layout</label>
    <div class="controls">
        <?= $this->Form->input('layout', array('type' => 'select', 'options' => array('default' => 'Active Club Layout', 'other' => 'Inactive Club Layout'), 'div' => false, 'label' => false, 'class' => 'input-xlarge')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Timezone</label>
    <div class="controls">
        <?php
        $identifiers = array();
        foreach(DateTimeZone::listIdentifiers() as $identifier) {
        $identifiers[$identifier] = $identifier;
        }
        ?>
        <?= $this->Form->input('timezone', array('type' => 'select', 'options' => $identifiers, 'div' => false, 'label' => false, 'class' => 'input-xlarge')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Parent Organization</label>
    <div class="controls">
        <?= $this->Form->input('parent_id', array('empty' => 'Choose the parent organisation of the club', 'type' => 'select', 'options' => $clubs, 'div' => false, 'label' => false, 'class' => 'input-xlarge')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Type</label>
    <div class="controls">
        <?= $this->Form->input('club_category_id', array('options' => $clubCategories, 'div' => false, 'label' => false)) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">Visible</label>
    <div class="controls">
        <?= $this->Form->input('visible', array('type' => 'checkbox', 'div' => false, 'label' => false, 'class' => 'input-xlarge')) ?>
    </div>
</div>
<div class="control-group">
    <label class="control-label">URLs for map files</label>
    <div class="controls">
        <?= $this->Form->input('use_map_urls', array('type' => 'checkbox', 'div' => false, 'label' => false, 'class' => 'input-xlarge')) ?> (only disable if you use the SVN repository for storing club maps)
    </div>
</div>
<?php
echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng'))); 
$this->Form->unlockField('Club.lat');
$this->Form->unlockField('Club.lng');
?>
<fieldset class="control-group">
    <label class="control-label">Location</label>
    <div class="controls">
        <?= $this->Leaflet->draggableMarker('ClubLat', 'ClubLng', 10, array('div' => array('width' => '80%', 'height' => '400px'))); ?>
        <p class="help-block">Drag the marker to the approximate location of the club</p>
    </div>
</fieldset>
<?= $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions'))) ?>


