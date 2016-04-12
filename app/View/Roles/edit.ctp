<header class="page-header">
    <h1>Role</h1>
</header>

<?php
echo $this->Form->create('Role', array('url' => array('action' => 'edit')));
echo $this->Form->input('name');
echo $this->Form->input('description');
echo $this->Form->end('Save');?>
