<h1>Role</h1>
<?php 
echo $this->Form->create('Role', array('action' => 'edit'));
echo $this->Form->input('name');
echo $this->Form->input('description');
echo $this->Form->end('Save');?>

