<?php
class Map extends AppModel {
	var $name = 'Map';
	var $displayField = 'name';
	var $actsAs = array('Containable');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array('MapStandard');
	var $hasOne = array('Resource');
	var $hasMany = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'map_id',
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
	
	var $virtualFields = array(
		'url' => 'CONCAT("/maps/view/", Map.id)'
	);
	/**
	* Find maps that haven't been used recently for an event
	*/
	function findRarelyUsed() {
	   $contain = array('Event.date', 'Event.url');
	   $conditions = array();
	   $maps = $this->find('all', array('contain' => $contain, 'conditions' => $conditions));
	   
	   foreach($maps as &$map) {
	       $lastUsed = $this->lastUsed($map);
	       $map['Map']['last_used'] = $lastUsed != null ? $lastUsed : null;
	       if(!empty($map['Event']))
	           $map['Event'] = Set::sort($map['Event'], '{n}.date', 'desc');
	   }
	   
	   return Set::sort($maps, '{n}.Map.last_used', 'asc');
	}
	
	function lastUsed(&$map) {
	   $lastUsed = null;
	   foreach($map['Event'] as $event) {
	       $date = new DateTime($event['utc_date']);
	       if($lastUsed < $date) {
	           $lastUsed = $date;
	       }
	   }
	   
	   return $lastUsed;
	}

}
?>
