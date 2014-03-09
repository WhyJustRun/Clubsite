<?php
class LiveResult extends AppModel {
    var $name = 'LiveResult';
    var $actsAs = array('Containable');
    protected $clubSpecific = false;

    var $belongsTo = array('Event');

}
