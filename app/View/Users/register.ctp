<div class="column span-8">
    <div class="column-box">
        <h2>Create an account</h2>
        An account is required to register/participate in club events.
    </div>
</div>
<div class="column span-16 last">
<?php
echo $this->Form->create('User', array('action' => 'register'));
echo $this->Form->input('name', array('label' => 'Name (first and lastname)', 'data-validate' => 'validate(required)'));
echo $this->Form->input('username', array('data-validate' => 'validate(required, username)'));
echo $this->Form->input('email', array('label' => 'E-mail', 'data-validate' => 'validate(required, email)'));
//echo $this->Form->input('subscribe', array('type'=>'checkbox', 'label'=>'', 'before'=>$this->Form->label('subscribe', "Subscribe to ". Configure::read('Club.acronym')."'s e-mailing list?<br>")));
echo $this->Form->input('club_id', array('empty' => 'Choose your home club', 'selected' => Configure::read('Club.id')));
echo $this->Form->input('si_number', array('label'=>'SportIdent number (if you have one)'));
echo $this->Form->input('referred_from',   array('label'=>'Referred by'));
echo $this->Form->input('password', array('data-validate' => 'validate(required)'));
echo $this->Recaptcha->display();
echo $this->Form->end('Register');
?>
   </div>
