<?php
class Series extends AppModel {
	var $name = 'Series';
	var $displayField = 'name';
	var $actsAs = array('Containable');
	var $validate = array(
		'id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'acronym' => array(
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'series_id',
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

   function findOrganizers($id) {
      $events = $this->Event->findAllBySeriesId($id);
      foreach($events as $event) {
         $eventId = $event["Event"]["id"];
         $org = $this->Event->Organizer->findAllByEventId($eventId);
         foreach($org as $or) {
            $userId = $or["Organizer"]["user_id"];
            $organizers[$userId]["name"] = $or["User"]["name"];
            $organizers[$userId]["id"] = $or["User"]["id"];
            $organizers[$userId]["role"] = $or["Role"]["name"];
         }
      }
      return $organizers;
   }
   function startDate($id) {
      $events = $this->Event->findAllBySeriesId($id, array('date'));
      if(count($events) == 0)
         return NULL;
      $startDate = 999999999999999;//$events[0]["Event"]["date"];
      foreach($events as $event) {
         if($event["Event"]["date"] < $startDate)
            $startDate = $event["Event"]["date"] ;
      }

      return $startDate;
   }
   function endDate($id) {
      $events = $this->Event->findAllBySeriesId($id, array('date'));
      if(count($events) == 0)
         return NULL;
      $endDate = -999999999999999;
      foreach($events as $event) {
         if($event["Event"]["date"] > $endDate)
            $endDate = $event["Event"]["date"] ;
      }
      return $endDate;
   }

}
?>
