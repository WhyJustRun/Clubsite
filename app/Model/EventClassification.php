<?php
App::uses('AppModel', 'Model');

class EventClassification extends AppModel {

	public $displayField = 'name';
    public $clubSpecific = false;
    
	public $hasMany = array(
		'Event'
	);

}
