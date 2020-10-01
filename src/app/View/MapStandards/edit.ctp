<header class="page-header">
    <h1>Map standard</h1>
</header>

<?php
echo $this->Form->create('MapStandard', array('class' => 'form-horizontal', 'url' => array('action' => 'edit')));
echo $this->Form->input('name');
$color = empty($this->data['MapStandard']['color']) ? 'rgba(0,0,0,1)' : $this->data['MapStandard']['color'];
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
<?php
echo $this->Form->input('description');
echo $this->Form->end('Save');
