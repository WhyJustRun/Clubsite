<header class="page-header">
    <h1>Edit Series</h1>
</header>

<?php echo $this->Form->create('Series', array('url' => 'edit', 'class' => 'form-horizontal'))?>
<?php
echo $this->Form->create('Series', array('url' => 'edit'));
echo $this->Form->input('acronym');
echo $this->Form->input('name');
$color = empty($this->data['Series']['color']) ? 'rgba(255,0,0,1)' : $this->data['Series']['color'];
?>
<div class="form-group">
    <label class="control-label col-sm-2">Color</label>
    <div class="col-sm-10">
        <div class="input-group color-picker">
            <?php echo $this->Form->input('color', array('value' => $color, 'div' => false, 'class' => 'form-control', 'label' => false)) ?>
            <span class="input-group-addon"><i></i></span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="optionsCheckbox">Information</label>
    <div class="col-sm-10">
        <?php echo $this->Form->input('information', array('div' => false, 'class' => 'wjr-wysiwyg', 'label' => false)) ?>
        <div class="help-block">
            <p>Information specific to the series is shown on event pages.</p>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="optionsCheckbox">Is Current</label>
    <div class="col-sm-10">
        <div class="checkbox">
            <?php echo $this->Form->input('is_current', array('label' => false, 'div' => false)) ?>
        </div>
    </div>
</div>
<?php echo $this->Form->end(array('label' => 'Save')) ?>
