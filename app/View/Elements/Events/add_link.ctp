<?php
if($this->requestAction('users/authorized/'.Configure::read('Privilege.Event.edit'))) {
	echo $this->Html->link('Add Event', '/events/edit', array('class' => 'button'));
}
?>
