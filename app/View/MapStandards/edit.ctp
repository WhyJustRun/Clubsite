<header class="page-header">
    <h1>Map standard</h1>
</header>

<?php
echo $this->Form->create('MapStandard', array('class' => 'form-horizontal', 'url' => 'edit'));
echo $this->Form->input('name');
echo $this->Form->input('description');
echo $this->Form->end('Save');
