<div class="mapStandards form">
<?php echo $this->Form->create('MapStandard');?>
	<fieldset>
 		<legend><?php __('Add Map Standard'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Map Standards', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Maps', true), array('controller' => 'maps', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map', true), array('controller' => 'maps', 'action' => 'add')); ?> </li>
	</ul>
</div>