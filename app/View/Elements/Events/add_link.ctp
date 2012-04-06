<?php
if($this->requestAction('users/authorized/'.Configure::read('Privilege.Event.edit'))) {
	echo $this->Html->link('<i class="icon-plus icon-white"></i> Event', '/events/edit', array('class' => 'btn btn-success', 'escape' => false));
}
?>
