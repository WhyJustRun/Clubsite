<header class="page-header">
    <h1>Edit Series</h1>
</header>

<?=$this->Form->create('Series', array('action' => 'edit', 'class' => 'form-horizontal'))?>
<?php 
echo $this->Form->create('Series', array('action' => 'edit'));
echo $this->Form->input('acronym', array('class' => 'input-small'));
echo $this->Form->input('name');
$color = empty($this->data['Series']['color']) ? 'rgba(255,0,0,1)' : $this->data['Series']['color'];
?>
<div class="control-group">
    <label class="control-label">Color</label>
    <div class="controls">
        <div class="input-append color colorpicker" data-color="<?= $color ?>" data-color-format="rgb">
          <?= $this->Form->input('color', array('class' => 'span2', 'value' => $color, 'div' => false, 'label' => false)) ?><span class="add-on"><i style="background-color: <?= $color ?>"></i></span>
        </div>
    </div>
</div>
<?php
echo $this->Html->css('colorpicker');
$this->Html->script('bootstrap-colorpicker', array('inline' => false));
?>
<script type="text/javascript">
$('.colorpicker').colorpicker();
</script>
<div class="control-group">
    <label class="control-label" for="optionsCheckbox">Description</label>
    <div class="controls">
        <?= $this->Form->input('description', array('div' => false, 'class' => 'input-xxxlarge oa-wysiwyg', 'label' => false)) ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="optionsCheckbox">Information</label>
    <div class="controls">
        <?= $this->Form->input('information', array('div' => false, 'class' => 'input-xxxlarge oa-wysiwyg', 'label' => false)) ?>
        <div class="help-block">
            <p>Information specific to the series is shown on event pages.</p>
        </div>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="optionsCheckbox">Is Current</label>
    <div class="controls">
        <?= $this->Form->input('is_current', array('label' => false, 'div' => false)) ?>
    </div>
</div>
<?= $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions'))) ?>
