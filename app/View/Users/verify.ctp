<h2>New Password</h2>

<?php
echo $this->Form->create('User', array('action' => 'verify'));
echo "<div class='input'><p><label>Name</label> <strong>".$user['User']['name']."</strong></p>";
echo "<p><label>Username</label> <strong>".$user['User']['username']."</strong></p></div>";
echo $this->Form->input('password');
echo $this->Form->input('password_confirm',array('type' => 'password', 'div' => array('class' => 'input password required'), 'value' => "", 'label' => 'Confirm Password')); 
echo $this->Form->hidden('token', array('value' => $token));
echo $this->Form->end('Login');
?>
