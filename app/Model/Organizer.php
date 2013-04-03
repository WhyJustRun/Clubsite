<?php
class Organizer extends AppModel {
    var $name = 'Organizer';
    var $actsAs = array('Containable');
    protected $clubSpecific = false; // associated with an event which is club specific

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
            'user_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
                    ),
                ),
            'role_id' => array(
                    'numeric' => array(
                        'rule' => array('numeric'),
                        //'message' => 'Your custom message here',
                        //'allowEmpty' => false,
                        //'required' => false,
                        //'last' => false, // Stop validation after this rule
                        //'on' => 'create', // Limit validation to 'create' or 'update' operations
                        ),
                    ),
            );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array(
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
                ),
            'Event' => array(
                'className' => 'Event',
                'foreignKey' => 'event_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
                ),
            'Role' => array(
                'className' => 'Role',
                'foreignKey' => 'role_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
                )
                );
    /**
     * Returns true if the user is authorized to edit the event
     */
    function isAuthorized($eventId, $userId ) {
        if(empty($userId) || empty($eventId)) {
            return false;
        } elseif($this->User->isAuthorized($userId, Configure::read('Privilege.Event.edit'))) {
            return true;
        } elseif($this->find('count', array('conditions' => array('Organizer.user_id' => $userId, 'Event.id' => $eventId))) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * An arbitrary algorithm to find people who haven't helped out recently, but are coming to multiple recent events. If it isn't working well, can try tweaking the date threshold, or the attendance threshold
     */
    function findVolunteers() {
        $dateThreshold = Configure::read('Event.planner.dateThreshold');
        $attendanceThreshold = Configure::read('Event.planner.attendanceThreshold');

        $recentAttendance = $this->Event->Course->Result->findRecentAttendance($dateThreshold, $attendanceThreshold);
        $recentOrganizers = $this->Event->Organizer->findRecent($dateThreshold);
        $clubId           =  Configure::read('Club.id');
        $clubMembers      = $this->User->find('list', array('conditions'=>array('User.club_id'=>"$clubId"), 'fields'=>array('User.id')));
        $offenders        = array_diff(array_keys($recentAttendance), array_keys($recentOrganizers)); 
# Offenders must be in the current club
        $offenders        = array_intersect($offenders, array_keys($clubMembers)); 

        $volunteers = array();
        foreach($offenders as $offender) {
            $volunteer = $this->User->find('first', array('conditions' => array('User.id' => $offender), 'recursive' => -1));
            $volunteer['User']['attended'] = $recentAttendance[$volunteer['User']['id']];
            array_push($volunteers, $volunteer);
        }

        return $volunteers;
    }

    function findRecent($from) {
        $from = Sanitize::escape($from->format('Y-m-d H:i:s'));
        $resultSet = $this->query("SELECT COUNT(`Organizer`.`user_id`) AS `User.organized`, `Organizer`.`user_id` FROM `organizers` AS `Organizer` LEFT JOIN `events` AS `Event` ON (`Event`.`id` = `Organizer`.`event_id`) WHERE `Event`.`date` > '$from' GROUP BY `Organizer`.`user_id`");
        $map = array();
        foreach($resultSet as $result) {
            $map[$result['Organizer']['user_id']] = $result[0]['User.organized'];
        }
        return $map;
    }
}
?>
