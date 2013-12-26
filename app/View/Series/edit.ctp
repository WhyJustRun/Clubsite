<header class="page-header">
    <h1>Edit Series</h1>
</header>

<?=$this->Form->create('Series', array('action' => 'edit', 'class' => 'form-horizontal'))?>
<?php 
echo $this->Form->create('Series', array('action' => 'edit'));
echo $this->Form->input('acronym');
echo $this->Form->input('name');
$color = empty($this->data['Series']['color']) ? 'rgba(255,0,0,1)' : $this->data['Series']['color'];
?>
<div class="form-group">
    <label class="control-label col-sm-2">Color</label>
    <div class="col-sm-10">
        <div class="input-group color-picker">
            <?= $this->Form->input('color', array('value' => $color, 'div' => false, 'class' => 'form-control', 'label' => false)) ?>
            <span class="input-group-addon"><i></i></span>
        </div>
    </div>
</div>
<?php
echo $this->Html->css('bootstrap-colorpicker.min');
$this->Html->script('bootstrap-colorpicker.min', array('inline' => false));
?>
<script type="text/javascript">
$('.color-picker').colorpicker();
</script>

<div class="form-group">
    <label class="control-label col-sm-2" for="optionsCheckbox">Information</label>
    <div class="col-sm-10">
        <?= $this->Form->input('information', array('div' => false, 'class' => 'oa-wysiwyg', 'label' => false)) ?>
        <div class="help-block">
            <p>Information specific to the series is shown on event pages.</p>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-2" for="optionsCheckbox">Is Current</label>
    <div class="col-sm-10">
        <div class="checkbox">
            <?= $this->Form->input('is_current', array('label' => false, 'div' => false)) ?>
        </div>
    </div>
</div>
<?= $this->Form->end(array('label' => 'Save')) ?>
