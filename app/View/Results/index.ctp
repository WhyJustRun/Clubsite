<div class="results index">
	<h2><?php __('Results');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('user_id');?></th>
			<th><?php echo $this->Paginator->sort('course_id');?></th>
			<th><?php echo $this->Paginator->sort('time');?></th>
			<th><?php echo $this->Paginator->sort('non_competitive');?></th>
			<th><?php echo $this->Paginator->sort('points');?></th>
			<th><?php echo $this->Paginator->sort('needs_ride');?></th>
			<th><?php echo $this->Paginator->sort('offering_ride');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($results as $result):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $result['Result']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($result['User']['name'], array('controller' => 'users', 'action' => 'view', $result['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($result['Course']['name'], array('controller' => 'courses', 'action' => 'view', $result['Course']['id'])); ?>
		</td>
		<td><?php echo $result['Result']['time']; ?>&nbsp;</td>
		<td><?php echo $result['Result']['non_competitive']; ?>&nbsp;</td>
		<td><?php echo $result['Result']['points']; ?>&nbsp;</td>
		<td><?php echo $result['Result']['needs_ride']; ?>&nbsp;</td>
		<td><?php echo $result['Result']['offering_ride']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $result['Result']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $result['Result']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $result['Result']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $result['Result']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Result', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Courses', true), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course', true), array('controller' => 'courses', 'action' => 'add')); ?> </li>
	</ul>
</div>
