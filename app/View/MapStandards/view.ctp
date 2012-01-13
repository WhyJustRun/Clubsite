<div class="mapStandards view">
<h2><?php  __('Map Standard');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapStandard['MapStandard']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapStandard['MapStandard']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $mapStandard['MapStandard']['description']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Map Standard', true), array('action' => 'edit', $mapStandard['MapStandard']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Map Standard', true), array('action' => 'delete', $mapStandard['MapStandard']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $mapStandard['MapStandard']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Map Standards', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map Standard', true), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Maps', true), array('controller' => 'maps', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Map', true), array('controller' => 'maps', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php __('Related Maps');?></h3>
	<?php if (!empty($mapStandard['Map'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Club Id'); ?></th>
		<th><?php __('Map Standard Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($mapStandard['Map'] as $map):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $map['id'];?></td>
			<td><?php echo $map['club_id'];?></td>
			<td><?php echo $map['map_standard_id'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'maps', 'action' => 'view', $map['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'maps', 'action' => 'edit', $map['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'maps', 'action' => 'delete', $map['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $map['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Map', true), array('controller' => 'maps', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
