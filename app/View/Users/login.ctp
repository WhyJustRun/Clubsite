<?= $this->Session->flash('auth'); ?>
<div class="row">
    <div class="span4">
        <h2>Sign in with OrienteerApp (WhyJustRun)</h2>
        <?php
        echo $this->Form->create('User', array('action' => 'login'));
        echo $this->Form->input('username', array('label' => '', 'placeholder' => 'Username', 'autofocus' => true));
        echo $this->Form->input('password', array('label' => '', 'placeholder' => 'Password'));
        echo $this->Form->end('Sign in', array('class' => 'btn btn-success'));
        ?>
    </div>
    <div class="span4">
        <h2>Forgot your account?</h2>
        <?php
        echo $this->Form->create('User', array('action' => 'forgot'));
        echo $this->Form->input('email', array('placeholder' => 'E-mail address', 'label' => ''));
        echo $this->Form->end('Remind me');
        ?>
    </div>
    <div class="span4">
        <h2>Need an account?</h2>
        <?= $this->Html->link('Register', '/users/register/', array('class' => 'btn btn-primary')) ?>
    </div>
</div>

