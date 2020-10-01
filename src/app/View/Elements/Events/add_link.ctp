<?php
if($this->requestAction('users/authorized/'.Configure::read('Privilege.Event.edit'))) {
    echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Event', '/events/edit', array('class' => 'btn btn-success', 'escape' => false));
}
