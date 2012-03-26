<?php
class Event extends AppModel {
	var $name = 'Event';
	var $displayField = 'name';
	var $actsAs = array('Containable');

	var $validate = array(
		// FIXME-RWP No Validation on datetime (date) field
		'lat' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'lng' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'is_ranked' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	var $virtualFields = array(
		'url' => 'CONCAT("/events/view/", Event.id)'
	);

	var $belongsTo = array(
		'Group',
		'Map',
		'Series',
		'EventClassification'
	);

	var $hasMany = array(
        'Course' => array('className'=>'Course','dependent'=>true),
		'Organizer'=> array('className'=>'Organizer','dependent'=>true)
	);

	function findAllBetween($startTimestamp, $endTimestamp) {
		$startTime = date("Y-m-d H:i:s", $startTimestamp);
		$endTime = date("Y-m-d H:i:s", $endTimestamp);
		$conditions = array("Event.date >=" => $startTime, "Event.date <=" => $endTime);
		
		return $this->find("all",array("conditions" => $conditions));
	}

   // Finds all events prior to event with id
   function findBeforeEvent($id) {
		$startTime = date("Y-m-d H:i:s", 0);
		$endTime = $this->field('date');
		
		$conditions = array("Event.date >=" => $startTime, "Event.date <=" => $endTime);
		
		return $this->find("all",array("conditions" => $conditions));
   }

}
?>
