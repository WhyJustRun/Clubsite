<div class="page-header">
    <h1>Create an account <small>Participate in club events</small></h1>
    
</div>
<?php
echo $this->Form->create('User', array('action' => 'register', 'class' => 'form-horizontal', 'data-validate' => 'ketchup'));
echo $this->Form->input('name', array('label' => 'Name', 'placeholder' => 'Full Name', 'data-validate' => 'validate(required)'));
echo $this->Form->input('username', array('data-validate' => 'validate(required, username)'));
echo $this->Form->input('email', array('label' => 'E-mail', 'data-validate' => 'validate(required, email)'));
echo $this->Form->input('club_id', array('empty' => 'Choose your home club', 'class' => 'span4', 'selected' => Configure::read('Club.id')));
echo $this->Form->input('si_number', array('placeholder' => 'Optional', 'label' => 'SportIdent #'));
echo $this->Form->input('referred_from', array('placeholder' => 'Optional', 'label' => 'Referred by'));
echo $this->Form->input('password', array('data-validate' => 'validate(required)'));
echo "<fieldset class='control-group'><div class='controls'>";
echo $this->Recaptcha->display();
echo "</div></fieldset>";
echo $this->Form->end(array('label' => 'Register', 'div' => array('class' => 'form-actions')));
?>

