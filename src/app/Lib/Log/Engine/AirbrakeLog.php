<?php
/*App::uses('CakeLogInterface', 'Log');
App::import('Vendor', 'Airbrake');

class AirbrakeLog implements CakeLogInterface {
    var $airbrake;
    
    function __construct($options = array()) {
        //Services_Airbrake::$apiKey = ;
        $config = Configure::read('Airbrake');
        $this->airbrake = new Services_Airbrake($config['apiKey'], $config['environment'], 'curl');
    }

    function write($type, $message) {
        //var_dump($message);
        $this->airbrake->errorHandler($type, $message, null, null);
        //$this->airbrake->exceptionHandler(new Exception($message));
    }
}*/
