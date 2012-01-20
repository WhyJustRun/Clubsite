<div class="column-box">
    <h2>My Settings</h2>
<?php
	echo $this->Session->flash('auth');
   //echo "<h2>User profile: " . $this->Session->read('Auth.User.username') . "</h2>";

    echo $this->Form->create('User', array('action' => 'edit'));
	echo $this->Form->input('name',    array('label'=>'Full name', 'value'=>$this->Session->read('Auth.User.name')));
//	echo $this->Form->input('username',array('value'=>$this->Session->read('Auth.User.username')));
	echo $this->Form->input('email',   array('value'=>$this->Session->read('Auth.User.email')));
//	echo $this->Form->input('club',    array('options' => array('GVOC' => 'Greater Vancouver Orinteering Club', 'VICO' => 'Victorienteers')));
	echo $this->Form->input('year_of_birth',   array('value'=>$this->Session->read('Auth.User.year_of_birth')));
	echo $this->Form->input('si_number',   array('value'=>$this->Session->read('Auth.User.si_number')));
	echo $this->Form->end('Modify');?>
</div>
<div class="column-box">
<?
   echo "<h2>Change password</h2>";
	echo $this->Form->create('User', array('action' => 'edit_change_password'));
	echo $this->Form->hidden('id',   array('value'=>$this->Session->read('Auth.User.id')));
	//echo $this->Form->input('password', array('label'=>'Old Password', 'type'=>'password'));
//	echo $this->Form->input('password');
	echo $this->Form->input('password', array('label'=>'New Password', 'type'=>'password', 'required'));
	echo $this->Form->input('new_password2', array('label'=>'New Password (confirm)', 'type'=>'password'));
	echo $this->Form->end('Modify');
   ?>
<?php if(0) {?>
<div class="input"><label>E-mail list</label></div>
<?php
       if(!$is_subscribed) {
          echo $this->Form->create('User', array('url' => '/Users/subscribe/'.$this->Session->read('Auth.User.id')));
          echo $this->Form->end("Subscribe"); 
       }
       else {
          echo $this->Form->create('User', array('url' => '/Users/unsubscribe/'.$this->Session->read('Auth.User.id')));
          echo $this->Form->end("Unsubscribe"); 
       }
   }
?>
</div>
<div class="column-box">
<h2>API</h2>
<p>As a WhyJustRun user, you have access to our API to post your event results, etc.</p>
API Key: <b><?= $this->Session->read('Auth.User.api_key') ?></b> (keep it secret!)
</div>
