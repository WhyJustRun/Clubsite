<?php
class Course extends AppModel {
	var $name = 'Course';
	var $displayField = 'name';
	var $actsAs = array('Containable');
	var $clubSpecific = false; // event is club specific
	var $hasOne = array('Resource');

	var $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Result' => array(
			'className' => 'Result',
			'foreignKey' => 'course_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	var $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
		),
	);
    
    function getDate($course_id) {
        return $this->Event->field('date');
    }
}
?>
