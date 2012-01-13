<?= $this->Session->flash('auth'); ?>
<div class="span-8 column column-box">
<?php
echo "<h2>Already have a WhyJustRun account?</h1>";
echo "Note: Accounts from the old GVOC website (orienteeringbc.ca/gvoc) can also be used here.";
echo $this->Form->create('User', array('action' => 'login'));
echo $this->Form->input('username', array('autofocus' => true));
echo $this->Form->input('password');
echo $this->Form->end('Login');
?>
</div>
<div class="span-8 column column-box">
<?php
echo "<h2>Forgot your account?</h1>";
echo $this->Form->create('User', array('action' => 'forgot'));
echo $this->Form->input('email');
echo $this->Form->end('Remind me');
?>
</div>
<div class="span-5 column column-box last">
<?php
//   echo "<h3>New to the whyjustrun.ca website? Create a new account:</h3>";
echo "<h2>Need an account?</h2>";
echo $this->Html->link('Register', '/users/register/', array('class' => 'button'));
?>
</div>
