<header class="page-header">
    <h1>Edit Club Information</h1>
</header>
<?php echo $this->Form->create('Club', array('class' => 'form-horizontal', 'data-validate' => 'ketchup', 'action' => 'edit')); ?>
<div class="form-group">
    <label class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('name', array('div' => false, 'label' => false, 'class' => 'form-control', 'data-validate' => 'validate(required)', 'required' => 'required')) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Acronym</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('acronym', array('class' => 'form-control', 'div' => false, 'label' => false, 'data-validate' => 'validate(required)', 'required' => 'required')) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Location</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('location', array('class' => 'form-control', 'div' => false, 'label' => false, 'data-validate' => 'validate(required)', 'required' => 'required')) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('description', array('class' => 'form-control', 'type' => 'textarea', 'div' => false, 'label' => false)) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Website URL</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('url', array('div' => false, 'label' => false, 'class' => 'form-control', 'data-validate' => 'validate(required)', 'required' => 'required')) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Facebook Page URL</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('facebook_page_id', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')) ?>
        <span class="help-block">Used on the home page for the news section</span>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Page Layout</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('layout', array('type' => 'select', 'options' => array('default' => 'Active Club Layout', 'other' => 'Inactive Club Layout'), 'div' => false, 'label' => false, 'class' => 'form-control')) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Timezone</label>
    <div class="col-sm-10">
        <?php
        $identifiers = array();
        foreach(DateTimeZone::listIdentifiers() as $identifier) {
        $identifiers[$identifier] = $identifier;
        }
        ?>
        <?php echo $this->Form->input('timezone', array('type' => 'select', 'options' => $identifiers, 'div' => false, 'label' => false, 'class' => 'form-control')) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Parent Organization</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('parent_id', array('empty' => 'Choose the parent organisation of the club', 'type' => 'select', 'options' => $clubs, 'div' => false, 'label' => false, 'class' => 'form-control')) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Type</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('club_category_id', array('options' => $clubCategories, 'div' => false, 'class' => 'form-control', 'label' => false)) ?>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Visible</label>
    <div class="col-sm-10">
        <div class="checkbox">
            <?php echo $this->Form->input('visible', array('type' => 'checkbox', 'div' => false, 'label' => false)) ?>
        </div>
    </div>
</div>
<fieldset class="form-group">
    <label class="col-sm-2 control-label">Location</label>
    <div class="col-sm-10">
      <?php
      echo $this->Form->hidden('lat', array('default' => Configure::read('Club.lat')));
      echo $this->Form->hidden('lng', array('default' => Configure::read('Club.lng')));

      $this->Form->unlockField('Club.lat');
      $this->Form->unlockField('Club.lng');
      ?>
      <div class="draggable-marker-map"
           data-lat-element="#ClubLat"
           data-lng-element="#ClubLng"
           data-zoom="10"
           style="height: 400px; width: 100%">
      </div>
        <p class="help-block">Drag the marker to the approximate location of the club</p>
    </div>
</fieldset>
<div class="form-group">
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'col-sm-offset-2 col-sm-10'))) ?>
</div>
