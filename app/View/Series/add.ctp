<div class="series form">
<?php echo $this->Form->create('Series');?>
	<fieldset>
 		<legend><?php __('Add Series'); ?></legend>
	<?php
		echo $this->Form->input('club_id');
		echo $this->Form->input('acronym');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Series', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Clubs', true), array('controller' => 'clubs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Club', true), array('controller' => 'clubs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Events', true), array('controller' => 'events', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Event', true), array('controller' => 'events', 'action' => 'add')); ?> </li>
	</ul>
</div>