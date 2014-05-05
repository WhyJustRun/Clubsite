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
                'allowEmpty' => true
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
    var $hasOne = array('LiveResult' => array('dependent' => true));

    function beforeSave(){
        parent::beforeSave();
        if(array_key_exists('custom_url', $this->data['Event'])) {
            if($this->data['Event']['custom_url'] === '') {
                $this->data['Event']['custom_url'] = NULL;
            }
        }
        return true;
    }

    function findOngoing($limit) {
        return $this->findEventsNearNow('ongoing', $limit);
    }

    function findUpcoming($limit) {
        return $this->findEventsNearNow('after', $limit);
    }

    function findPast($limit) {
        return $this->findEventsNearNow('before', $limit);
    }

    private function findEventsNearNow($beforeOrAfterNow, $limit) {
        $time = new DateTime();
        $time = $time->format('Y-m-d H:i:s');
        if ($beforeOrAfterNow === 'before') {
            $conditions = array(
                'AND' => array(
                    'Event.date <=' => $time,
                    'NOT' => array(
                        'AND' => array(
                            'NOT' => array('Event.finish_date' => null),
                            'Event.finish_date >=' => $time,
                        )
                    )
                )
            );
            $order = array('Event.date DESC');
        } else if ($beforeOrAfterNow === 'ongoing') {
            $conditions = array(
                'AND' => array(
                    'Event.date <=' => $time,
                    'NOT' => array('Event.finish_date' => null),
                    'Event.finish_date >=' => $time,
                )
            );
            $order = array('Event.date DESC');
        } else if ($beforeOrAfterNow === 'after') {
            $conditions = array('Event.date >=' => $time);
            $order = array('Event.date ASC');
        } else {
            die("Event.findEventsNearNow: invalid parameter: $beforeOrAfterNow");
        }

        $options = array(
            'limit' => $limit,
            'contain' => array('Series.id', 'Series.name', 'EventClassification.name'),
            'conditions' => $conditions,
            'order' => $order,
        );
        return $this->find('all', $options);
    }

    function findAllBetween($startTimestamp, $endTimestamp) {
        $startTime = date("Y-m-d H:i:s", $startTimestamp);
        $endTime = date("Y-m-d H:i:s", $endTimestamp);
        $conditions = array("Event.date >=" => $startTime, "Event.date <=" => $endTime);

        return $this->find("all", array("conditions" => $conditions));
    }

    // Finds all events prior to event with id
    function findBeforeEvent($id) {
        $startTime = date("Y-m-d H:i:s", 0);
        $endTime = $this->field('date');

        $conditions = array("Event.date >=" => $startTime, "Event.date <=" => $endTime);

        return $this->find("all",array("conditions" => $conditions));
    }

}
