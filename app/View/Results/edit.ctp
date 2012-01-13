<div class="results form">
<?php echo $this->Form->create('Result');?>
	<fieldset>
 		<legend><?php __('Edit Result'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('course_id');
		echo $this->Form->input('time');
		echo $this->Form->input('non_competitive');
		echo $this->Form->input('points');
		echo $this->Form->input('needs_ride');
		echo $this->Form->input('offering_ride');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Result.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Result.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Results', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses', true), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course', true), array('controller' => 'courses', 'action' => 'add')); ?> </li>
	</ul>
</div>