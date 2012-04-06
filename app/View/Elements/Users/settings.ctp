<h2>My Settings</h2>
<?php
echo $this->Session->flash('auth');
echo $this->Form->create('User', array('action' => 'edit'));
echo $this->Form->input('name',    array('label'=>'Full name', 'value'=>$this->Session->read('Auth.User.name')));
echo $this->Form->input('email',   array('value'=>$this->Session->read('Auth.User.email')));
echo $this->Form->input('si_number',   array('label' => 'SI Number', 'value'=>$this->Session->read('Auth.User.si_number')));
echo $this->Form->end('Save');
?>
<h2>Change Password</h2>
<?php
echo $this->Form->create('User', array('action' => 'edit_change_password'));
echo $this->Form->hidden('id',   array('value'=>$this->Session->read('Auth.User.id')));
echo $this->Form->input('password', array('label'=>'New Password', 'type'=>'password', 'required'));
echo $this->Form->input('new_password2', array('label'=>'New Password (confirm)', 'type'=>'password'));
echo $this->Form->end('Save');
?>
