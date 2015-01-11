<?php
App::uses('Sanitize', 'Utility');

class Result extends AppModel {
    var $name = 'Result';
    var $displayField = 'time_seconds';
    var $actsAs = array('Containable');
    var $clubSpecific = false;
    var $validate = array(
        'id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'course_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        // IOF Standard participant statuses
        'status' => array(
            'status' => array(
                'rule' => array('inList', array('inactive', 'did_not_start', 'active', 'finished', 'ok', 'mis_punch', 'did_not_finish', 'disqualified', 'not_competing', 'sport_withdrawal', 'over_time', 'moved', 'moved_up', 'cancelled')),
                'required' => false, // Defaults to 'ok'
            ),
        ),
    );

    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Registrant' => array(
            'className' => 'User',
            'foreignKey' => 'registrant_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Course' => array(
            'className' => 'Course',
            'foreignKey' => 'course_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => false,
            'conditions' => array('Course.event_id = Event.id'),
            'fields' => '',
            'order' => ''
        )
    );

    function isAuthorized($id, $userID) {
        $result = $this->find('first', array('conditions' => array('Result.id' => $id), 'contain' => array('Course.Event.id')));
        if ($result === null) {
            return false;
        }
        return $this->Course->Event->Organizer->isAuthorized($result["Course"]["Event"]["id"], $userID);
    }

    /**
     * Returns a map of people who have attended multiple events recently to the number of events attended, ordered by who has come the most
     */
    function findRecentAttendance($from, $minimumRaces = null) {
        $from = Sanitize::escape($from->format('Y-m-d H:i:s'));
        $resultSet = $this->query("SELECT COUNT(`Result`.`user_id`) AS `User.attended`, `Result`.`user_id` FROM `results` AS `Result` LEFT JOIN `courses` AS `Course` ON (`Result`.`course_id` = `Course`.`id`) LEFT JOIN `events` AS `Event` ON (`Event`.`id` = `Course`.`event_id`) WHERE `Event`.`date` > '$from' GROUP BY `Result`.`user_id` ORDER BY COUNT(`Result`.`user_id`) DESC");
        $map = array();
        foreach($resultSet as $result) {
            if(!$minimumRaces || $result[0]['User.attended'] >= $minimumRaces) {
                $map[$result['Result']['user_id']] = $result[0]['User.attended'];
            }
        }

        return $map;
    }
}
