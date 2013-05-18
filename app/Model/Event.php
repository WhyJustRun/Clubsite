<?php
class Event extends AppModel {
    var $name = 'Event';
    var $displayField = 'name';
    var $actsAs = array('Containable');

    var $validate = array(
        'lat' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true,
            ),
        ),
        'lng' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true,
            ),
        ),
        'is_ranked' => array(
            'boolean' => array(
                'rule' => array('boolean'),
            ),
        ),
        'custom_url' => array(
            'rule' => 'url',
            'allowEmpty' => true,
        ),
        'registration_url' => array(
            'rule' => 'url',
            'allowEmpty' => true,
        ),
        'results_url' => array(
            'rule' => 'url',
            'allowEmpty' => true,
        ),
        'routegadget_url' => array(
            'rule' => 'url',
            'allowEmpty' => true,
        ),
    );

    var $virtualFields = array(
        'url' => 'CONCAT("/events/view/", Event.id)'
    );

    var $belongsTo = array('Map', 'Series', 'EventClassification', 'Club');

    var $hasMany = array(
        'Course' => array('className' => 'Course', 'dependent' => true),
        'Organizer'=> array('className' => 'Organizer', 'dependent' => true)
    );

    function beforeSave(){
        parent::beforeSave();
        if(array_key_exists('custom_url', $this->data['Event'])) {
            if($this->data['Event']['custom_url'] === '') {
                $this->data['Event']['custom_url'] = NULL;
            }
        }
        return true;
    }

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
