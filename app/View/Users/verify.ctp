<header class="page-header">
    <h1>Reset Password</h1>
</header>

<?php
echo $this->Form->create('User', array('action' => 'verify', 'class' => 'form-horizontal')); ?>
<fieldset class="control-group">
    <label class="control-label">Name</label>
    <div class="controls">
        <strong><span class="uneditable-input"><?= $user['User']['name'] ?></span>
    </div>
</fieldset>
<fieldset class="control-group">
    <label class="control-label">Username</label>
    <div class="controls">
        <strong><span class="uneditable-input"><?= $user['User']['username'] ?></span>
    </div>
</fieldset>
<?php echo $this->Form->input('password');
echo $this->Form->input('password_confirm',array('type' => 'password', 'div' => array('class' => 'input password required'), 'value' => "", 'label' => 'Confirm Password')); 
echo $this->Form->hidden('token', array('value' => $token));
echo $this->Form->end(array('label' => 'Sign in', 'class' => 'btn btn-primary', 'div' => array('class' => 'form-actions')));
?>

