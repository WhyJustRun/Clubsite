<?php
App::uses('CakeLogInterface', 'Log');
App::uses('CakeEmail', 'Network/Email');

class EmailLog implements CakeLogInterface {
    function __construct($options = array()) {

    }

    // Reduce the email volume
    function shouldEmail($type, $error) {
        return
            (strpos($error, '[MissingControllerException]') === false) &&
            (strpos($error, '[MissingActionException]') === false) &&
            (strpos($error, 'SMTP Error: 421 4.7.0 Temporary System Problem') === false);
    }

    function write($type, $error) {
        if ($this->shouldEmail($type, $error)) {
            $emails = Configure::read('Log.Emails');
            if (count($emails) > 0) {
                $email = new CakeEmail('default');
                $email->emailFormat('text');
                $email->from(array('noreply@whyjustrun.ca' => 'WhyJustRun'));
                $email->to($emails);
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
    }
}
