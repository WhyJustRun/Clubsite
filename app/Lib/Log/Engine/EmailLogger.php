<?php
App::uses('CakeLogInterface', 'Log');
App::uses('CakeEmail', 'Network/Email');

class EmailLogger implements CakeLogInterface {
    function __construct($options = array()) {
        
    }

    function write($type, $error) {
        $email = new CakeEmail('default');
        $email->emailFormat('text');
        $email->from(array('noreply@whyjustrun.ca' => 'WhyJustRun'));
        $email->to(Configure::read('Log.Emails'));
        $email->subject('WhyJustRun '.$type);
        
        $message = "
URL: ".$_SERVER['REQUEST_URI']."
Method: ".$_SERVER['REQUEST_METHOD']."

";
        $message .= print_r(getallheaders(), true);
        $message .= "
        
        ";
        $message .= print_r($GLOBALS, true);
        $message .= "
        
        ";
        $message .= $error;
        $email->send($message);
    }
}
?>