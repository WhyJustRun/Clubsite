<?php
App::uses('CakeLogInterface', 'Log');
App::uses('CakeEmail', 'Network/Email');

class EmailLog implements CakeLogInterface {
    function __construct($options = array()) {

    }

    // Reduce the email volume
    function shouldEmail($type, $error, $url) {
        return
            (strpos($url, 'wp-admin') === false) &&
            (strpos($error, '`action` is deprecated') === false) &&
            (strpos($error, '[MissingControllerException]') === false) &&
            (strpos($error, '[NotFoundException]') === false) &&
            (strpos($error, '[MissingViewException]') === false) &&
            (strpos($error, '[MissingActionException]') === false) &&
            (strpos($error, 'SMTP Error: 421 4.7.0 Temporary System Problem') === false) &&
            (strpos($error, 'session_start(): The session id is too long or contains') === false);
    }

    function write($type, $error) {
        $url = $_SERVER['REQUEST_URI'];
        if ($this->shouldEmail($type, $error, $url)) {
            $emails = Configure::read('Log.Emails');
            if (count($emails) > 0) {
                $email = new CakeEmail('default');
                $email->emailFormat('text');
                $email->from(array('noreply@whyjustrun.ca' => 'WhyJustRun'));
                $email->to($emails);
                $email->subject('WhyJustRun '.$type);

                $message = "
    URL: ".$url."
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
