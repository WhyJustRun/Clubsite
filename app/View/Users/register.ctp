   <div class="column span-8">
      <div class="column-box">
         <h2>Create an account</h2>
         An account is required to register/participate in club events.
      </div>
      <div class="column-box">
         <h2>Privacy policy</h2>
            <p>Your <b>e-mail</b> address is used for password recovery and for our e-mailing list
            (if specified).</p>

            <p>We are required to report the <b>Year of birth</b>, <b>Postal code</b>, and <b>Gender</b>
            to Sports BC for membership purposes. This information is not used for anything else</p>
            
            <!--
            <p>The <b>Phone number</b> helps us in emergency cases when the participant has not returned
            at an event.</p>
            -->
            <p>The <b>SportIdent number</b>, is used for our bigger events that use the sport ident timing
            system. Having the correct SI number saves us time setting up the event.</p>
         
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
	echo $this->Form->input('year_of_birth');
	echo $this->Form->input('si_number', array('label'=>'SportIdent number'));
	echo $this->Form->input('referred_from',   array('label'=>'Referred by'));
	echo $this->Form->input('password', array('data-validate' => 'validate(required)'));
	echo $this->Recaptcha->display();
	echo $this->Form->end('Register');
?>
   </div>
