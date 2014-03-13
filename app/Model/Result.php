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
        'points' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true,
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

    function calculatePoints($course_id) {
        $meanTime   = $this->invMeanInvTimeByCourse($course_id);
        $meanPoints = $this->meanIncomingPoints($course_id);
        $results    = $this->findAllByCourseId($course_id);
        $isValidCourse  = $this->isValidCourse($course_id);
        $numValid   = $this->numValidRunners($course_id);
        foreach($results as &$result) {
            if(!empty($result["Result"]["time_seconds"])) {
                $currPoints = $meanPoints * $meanTime / $result["Result"]["time_seconds"];
                if($isValidCourse && $result["Result"]["status"] == 'ok') {
                    $result["Result"]["points"] = $currPoints;
                }
                else{
                    $result["Result"]["points"] = NULL;
                }
            }
            else{
                $result["Result"]["points"] = NULL;
            }
            $this->id = $result['Result']['id'];
            $this->saveField('points', $result["Result"]["points"]);
        }
    }

    function isValidCourse($courseId) {
        $course = $this->Course->findById($courseId);
        return $this->numValidRunners($courseId) >= 3 && !$course['Course']['is_score_o'];
    }

    // Compute inverse mean inverse time
    function invMeanInvTimeByCourse($course_id) {
        $conditions = array("course_id = " => $course_id);
        $data = $this->find("all", array("conditions" => $conditions));
        $meanTime = 0;
        $counter = 0;
        foreach ($data as $value=>$key) {
            $user_id = $key["Result"]["user_id"];
            if(!empty($key["Result"]["time_seconds"]) && $key["Result"]["status"] == 'ok' && $this->isValidRunner($course_id, $user_id)) {
                $meanTime = $meanTime + 1. / $key["Result"]["time_seconds"];
                $counter += 1;
            }
        }
        if($counter == 0)
            return NULL;
        else
            return $meanTime = 1/($meanTime / $counter);
    }
    function isValidRunner($course_id, $user_id) {
        $courseDate = $this->Course->getDate($course_id);
        $lowerDate  = strtotime($courseDate) - 2 * 86400 * 365;
        $lowerDate  = date('Y-m-d h:i:s', $lowerDate);

        $conditions = array("user_id = " => $user_id, "status" => 'ok', "time_seconds > "=>0, "Event.date < " => $courseDate, "Event.date > " => $lowerDate);

        $data = $this->find("all", array("conditions" => $conditions));
        if(count($data) < 5)
            return false;
        else
            return true;
    }
    // Compute inverse mean inverse time
    function meanTimeByCourse($course_id) {
        $conditions = array("course_id = " => $course_id);
        $data = $this->find("all", array("conditions" => $conditions));
        $meanTime = 0;
        $counter = 0;
        foreach ($data as $value=>$key) {
            if(!empty($key["Result"]["time_seconds"])) {
                $meanTime += $key["Result"]["time_seconds"];
                $counter += 0;
            }
        }
        if($counter == 0)
            return NULL;
        else
            return $meanTime / $counter;
    }

    function meanPoints($start_date, $end_date) {
        $this->User->bindModel(array('hasOne' => array('Club')));
        $points = $this->find('all', array(
                    'fields'=>array(
                        'User.id','User.name',
                        'User.club_id',
                        'Event.name',
                        'AVG(Result.points) as points',
                        'COUNT(Result.points) as count'),
                    'conditions' => array(
                        'Result.points >' => 0,
                        'Result.time_seconds >' => 0,
                        'Event.date >= ' => $start_date,
                        'Event.date <= ' => $end_date),
                    'group' => 'User.id',
                    'limit' => '20',
                    'order' => 'points desc'));
        foreach($points as &$point) {
            $club = $this->User->findById($point["User"]["id"]);
            $point["Club"] = $club["Club"];
        }

        return $points;
    }

    function meanPointsByUser($course_id, $user_id) {
        $courseDate = $this->Course->getDate($course_id);
        $lowerDate  = strtotime($courseDate) - 2 * 86400 * 365;
        $lowerDate  = date('Y-m-d h:i:s', $lowerDate);

        $conditions = array("user_id = " => $user_id, "status = " => 'ok', "time_seconds > " => 0, "Event.date < " =>$courseDate, "Event.date > " => $lowerDate);
        $data = $this->find("all", array("conditions" => $conditions));
        $meanPoints = 0;
        $counter = 0;
        foreach ($data as $value => $key) {
            $currPoints = $key["Result"]["points"];
            if($currPoints != 0){
                $meanPoints = $meanPoints + $currPoints;
                $counter += 1;
            }
        }
        // Need 3 races to count as a valid user
        if($counter < 3)
            return NULL;
        else
            return $meanPoints / $counter;
    }

    function numValidRunners($course_id) {
        $entries = $this->findAllByCourseId($course_id);
        $counter = 0;
        foreach ($entries as $entry) {
            $user_id = $entry["Result"]["user_id"];
            if (!empty($entry["Result"]["time_seconds"]) && $entry["Result"]["status"] == 'ok' && $this->isValidRunner($course_id, $user_id))
            {
                $counter += 1;
            }
        }
        return $counter;
    }

    function meanIncomingPoints($course_id) {
        $entries = $this->findAllByCourseId($course_id);
        $meanPoints = 0;
        $counter = 0;
        foreach ($entries as $entry) {
            $currPoints = $this->meanPointsByUser($course_id, $entry["Result"]["user_id"]);
            if($currPoints != NULL && !empty($entry["Result"]["time_seconds"]) && $entry["Result"]["status"] == 'ok') {
                $meanPoints += $currPoints;
                $counter += 1;
            }
        }
        if($counter == 0)
            return NULL;
        else
            return $meanPoints / $counter;
    }

    function points($course_id) {
        $entries = $this->findAllByCourseId($course_id);
        $meanIncoming = $this->meanIncomingPoints($course_id);
        $meanTime = $this->invMeanInvTimeByCourse($course_id);
        $points = array();
        foreach($entries as $entry) {
            $user = $entry["Result"]["user_id"];
            $points[$user] = $meanTime / $entry["Result"]["time_seconds"] * $meanIncoming;
        }
        return $points;
    }

    function isAuthorized($id, $userID) {
        $result = $this->find('first', array('conditions' => array('Result.id' => $id), 'contain' => array('Course.Event.id')));
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
