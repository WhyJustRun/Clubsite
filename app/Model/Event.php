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

	
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Map' => array(
			'className' => 'Map',
			'foreignKey' => 'map_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Series' => array(
			'className' => 'Series',
			'foreignKey' => 'series_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	var $hasMany = array(
		'Course' => array(
			'className' => 'Course',
			'foreignKey' => 'event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Organizer' => array(
			'className' => 'Organizer',
			'foreignKey' => 'event_id',
			'dependent' => false,
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

	function findAllBetween($startTimestamp, $endTimestamp) {
		$startTime = date("c", $startTimestamp);
		$endTime = date("c", $endTimestamp);
		
		$conditions = array("Event.date >=" => $startTime, "Event.date <=" => $endTime);
		
		return $this->find("all",array("conditions" => $conditions));
	}

   // Finds all events prior to event with id
   function findBeforeEvent($id) {
		$startTime = date("c", 0);
		$endTime = $this->field('date');
		
		$conditions = array("Event.date >=" => $startTime, "Event.date <=" => $endTime);
		
		return $this->find("all",array("conditions" => $conditions));
   }

}
?>
