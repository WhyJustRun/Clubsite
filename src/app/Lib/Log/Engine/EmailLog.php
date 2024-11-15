<?php
App::uses('CakeLogInterface', 'Log');
App::uses('CakeEmail', 'Network/Email');

class EmailLog implements CakeLogInterface {
    function __construct($options = array()) {

    }

    // Reduce the email volume
    function shouldEmail($type, $error, $url) {
        $excludedErrors = [
            '[MissingControllerException]',
            '[NotFoundException]',
            '[MissingViewException]',
            '[MissingActionException]',
            'SMTP Error: 421 4.7.0 Temporary System Problem',
            'session_start(): The session id is too long or contains',
            // This may indicate a legitimate issue in some cases, however it is noisy.
            'Request was blackholed of type: auth'
        ];

        foreach ($excludedErrors as $excludedError) {
            if (strpos($error, $excludedError) !== false) {
                return false;
            }
        }

        return strpos($url, 'wp-admin') === false;
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
                $message .= print_r($this->getAllHeaders(), true);
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

    private function getAllHeaders() {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
    
}
