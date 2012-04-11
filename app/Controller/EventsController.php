<?php
class EventsController extends AppController {

    var $name = 'Events';

    var $components = array(
        'RequestHandler',
        'Media' => array(
            'type' => 'Event',
            'allowedExts' => array('xml'),
            'thumbnailSizes' => array('')
        )
    );
    var $helpers = array("Time", "Geocode", "Form", "TimePlus", 'Leaflet', 'Markdown', 'Session', 'Media');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'upcoming', 'past', 'major', 'view', 'rendering', 'planner', 'embed');
    }

    // TODO-RWP Move ajax request $starTimestamp, $endTimestamp to separate call
    function index($startTimestampOrDate = null, $endTimestamp = null) {
        $this->set('title_for_layout', 'Calendar');
        // If is a date
        if(strpos($startTimestampOrDate, "-")) {
            $dateParts = explode("-", $startTimestampOrDate);
            $this->set('year', $dateParts[2]);
            $this->set('month', $dateParts[1]);
            $this->set('day', $dateParts[0]);
        } else {
            $this->set("events", array());
            if(!empty($startTimestamp) && !empty($endTimestamp)) {
                $this->set("events", $this->Event->findAllBetween($startTimestamp,$endTimestamp));
            }

            $this->set('year', date('Y'));
            $this->set('month', date('m'));
            $this->set('day', date('d'));
        }

        $this->set('series', $this->Event->Series->findAllByIsCurrent(1));
    }

    // Displays a rendering of the results (manually uploaded)
    function rendering($id) {
        $this->Media->display($id);
    }

    function edit($id = null) {
        if($id == null) {
            // Add event
            $this->checkAuthorization(Configure::read('Privilege.Event.edit'));
            $this->set('title_for_layout', 'Add Event');
            $this->_setLists();
            $this->set('type', 'Add');
        } else {
            // Edit event
            $this->set('title_for_layout', 'Edit Event');
            $this->set('type', 'Edit');
            $this->_checkEditAuthorization($id);
            $this->_setLists();
            $this->Event->id = $id;
        }


        if ($this->request->is('post')) {
            // Organizer data from JSON
            $this->_parseJson();
            
            $this->request->data['Event']['date'] = $this->request->data['Event']['date']." ".$this->request->data['Event']['time'];

            unset($this->request->data['Event']['time']);

            // Don't save the default location
            if(floatval($this->request->data['Event']['lat']) == Configure::read('Club.lat') && floatval($this->request->data['Event']['lng']) == Configure::read('Club.lng')) {
                $this->request->data['Event']['lat'] = null;
                $this->request->data['Event']['lng'] = null;
            }

            // Delete old organizers, as they will be re-created
            $this->Event->Organizer->deleteAll(array('Organizer.event_id' => $this->request->data["Event"]["id"]));

            if ($this->Event->saveAll($this->request->data)) {
                $this->Session->setFlash('The event has been updated.', 'flash_success');
                $this->redirect('/events/view/'.$this->Event->id);
            }
            else {
                $this->Session->setFlash('The event could not be updated.');
            }
        }

        $this->_editData();

    }

    function delete($id) {
        $this->checkAuthorization(Configure::read('Privilege.Event.delete'));
        if(!empty($id)) {
            $cascade = true;
            $this->Event->delete($id, $cascade);
            $this->Session->setFlash('The event was deleted.', 'flash_success');
        }
        else {
            $this->Session->setFlash('No event id provided.');
        }
        $this->redirect("/events/");
    }

    function planner() {
        $this->set('title_for_layout', 'Event Planner');
        $this->set('maps', $this->Event->Map->findRarelyUsed());
        $this->set('users', $this->Event->Organizer->findVolunteers());
    }

    function printableEntries($id) {
        $contain = array(
            'Series', 
            'Course' => array(
                'Result' => array('User.name', 'User.id', 'User.username', 'User.si_number', 'User.is_member')
            )
        );
        $event = $this->Event->find('first', array('conditions' => array('Event.id' => $id), 'contain' => $contain));
        // Sort according to name
        $event["Course"] = @Set::sort($event["Course"], "{n}.Result.{s}.User.name", 'asc');
        foreach ($event["Course"] as &$course) {
            $course["Result"] = @Set::sort($course["Result"], "{n}.User.name", 'asc');
        }
        $this->set('event', $event);
        $this->layout = 'printable';

    }
    function uploadMaps($id=null) {
        $this->_checkEditAuthorization($id);
        $this->set('title_for_layout', 'Upload Course Maps');
        $this->set('courses', $this->Event->Course->findAllByEventId($id));
        $this->set('id', $id);
    }

    function _setLists() {
        $this->set('maps', $this->Event->Map->find('list', array('order'=>'Map.name')));
        $this->set('series', $this->Event->Series->find('list'));
        $this->set('eventClassifications', $this->Event->EventClassification->find('list'));

    }

    function view($id = null) {
        $contain = array(
            'Series', 
            'Map', 
            'EventClassification',
            'Organizer' => array(
                'User' => array(
                    'fields' => array('id', 'name', 'username')
                ), 'Role'
            ), 
            'Course' => array(
                'Result' => array('Registrant.id', 'User.name', 'User.id', 'User.username', 'User.si_number')
            )
        );
        $event = $this->Event->find('first', array('conditions' => array('Event.id' => $id), 'contain' => $contain));
        $user = AuthComponent::user();

        if(!$event) {
            $this->Session->setFlash("The event requested couldn't be found.");
            $this->redirect('/');
            return;
        }

        $startTime = new DateTime($event["Event"]["utc_date"]);
        if($this->_isBeforeNow($startTime)) {
            $event["Event"]["completed"] = true;
        } else {
            $event["Event"]["completed"] = false;
        }

        foreach($event["Course"] as &$course) {
            $course["Result"] = @Set::sort($course["Result"], "{n}.User.name", 'asc');
            $course["registered"] = false;

            if($this->Auth->loggedIn()) {
                foreach($course["Result"] as $result) {
                    if($user["id"] === $result["User"]["id"]) {
                        $course["registered"] = true;
                        break;
                    }
                }
            }
        }

        $this->set('title_for_layout', $event["Event"]["name"]);
        $this->set('event', $event);
        $this->set('edit', $this->Event->Organizer->isAuthorized($id, AuthComponent::user('id')));
    }
    function results($id) {
        return $this->view($id);
    }

    function editResults($id) {
        $this->set('title_for_layout', 'Edit Results');
        $this->_checkEditAuthorization($id);
        $this->Event->id = $id;
        if ($this->request->is('post')) {
            $allowedCoursesResult = $this->Event->Course->find('all', array('recursive' => -1, 'conditions' => array('Course.event_id' => $id)));
            $allowedCourses = array();
            foreach($allowedCoursesResult as $allowedCourseResult) {
                array_push($allowedCourses, $allowedCourseResult['Course']['id']);
            }
            $courses = json_decode($this->request->data["Event"]["courses"]);
            $updatedResults = array();
            foreach($courses as $course) {
                // Security check: make sure the course being edited is actually part of the event being edited
                if(!in_array($course->id, $allowedCourses)) {
                    $this->redirect("/");
                }
                
                foreach($course->results as $result) {
                    $processedResult = array();
                    $processedResult["user_id"] = $result->user->id;
                    $processedResult["course_id"] = $course->id;
                    $processedResult["time"] = $this->_timeFromParts($result->hours, $result->minutes, $result->seconds);
                    $processedResult['status'] = empty($result->status) ? 'ok' : $result->status;
                    $processedResult["needs_ride"] = empty($result->needs_ride) ? false : $result->needs_ride;

                    if(empty($result->offering_ride)) {
                        $processedResult["offering_ride"] = false;
                    } else {
                        $processedResult["offering_ride"] = $result->offering_ride;
                    }

                    if(!empty($result->id)) {
                        $processedResult["id"] = $result->id;
                    }
                    array_push($updatedResults, $processedResult);					
                }
                if(empty($updatedResults) || $this->Event->Course->Result->saveAll($updatedResults)) {
                    $this->Event->Course->Result->calculatePoints($course->id);
                    $this->Event->saveField('results_posted', $this->request->data["Event"]["results_posted"]);
                }
            }
            $this->redirect("/events/view/${id}");
        }
        $this->set('eventId', $id);
    }

    function upcoming($limit)
    {
        $time = new DateTime();
        return $this->Event->find('all', array('limit' => $limit, 'contain' => array('Series.id', 'Series.name', 'EventClassification.name'), 'conditions' => array('Event.date >=' => $time->format("Y-m-d H:i:s")), 'order' => 'Event.date ASC'));
    }
    function major($limit)
    {
        $time = new DateTime();
        return $this->Event->find('all', array('limit' => $limit, 'contain' => array('Series.id', 'Series.name', 'EventClassification.name'), 'conditions' => array('Event.date >=' => $time->format("Y-m-d H:i:s"), 'Event.event_classification_id <= ' => 4), 'order' => 'Event.date ASC'));
    }

    function past($limit)
    {
        $time = new DateTime();
        return $this->Event->find('all', array('limit' => $limit, 'contain' => array('Series.id', 'Series.name'), 'conditions' => array('Event.date <=' => $time->format("Y-m-d H:i:s")), 'order' => 'Event.date DESC'));
    }

    function _isBeforeNow($dateTime) {
        $now = new DateTime();
        if(($dateTime->getTimestamp() - $now->getTimestamp()) > 0) {
            return false;
        } else {
            return true;
        }
    }


    function _checkEditAuthorization($id) {
        // Check authorization
        if(!$this->Event->Organizer->isAuthorized($id, AuthComponent::user('id'))) {
            $this->Session->setFlash('You are not authorized to edit this event.');
            $this->redirect('/events/view/'.$id);
        }
    }

    function _parseJson() {
        $organizers = json_decode($this->request->data["Event"]["organizers"]);
        unset($this->request->data["Event"]["organizers"]);
        if(count($organizers) > 0) {
            $this->request->data["Organizer"] = array();
            foreach($organizers as $organizer) {
                $convertedOrganizer = array( 'role_id' => $organizer->role->id, 'user_id' => $organizer->id);
                // Event id won't be known for a new event, it will be automagically added by CakePHP upon save
                if(!empty($this->request->data["Event"]['id'])) {
                    $convertedOrganizer['event_id'] = $this->request->data["Event"]['id'];
                }
                array_push($this->request->data["Organizer"], $convertedOrganizer);
            }
        }

        $courses = json_decode($this->request->data["Event"]["courses"]);
        unset($this->request->data["Event"]["courses"]);
        if(count($courses) > 0) {
            $this->request->data["Course"] = array();
            foreach($courses as $course) {
                array_push($this->request->data["Course"], array('id' => $course->id, 'name' => $course->name, 'description' => $course->description, 'distance' => $course->distance, 'climb' => $course->climb));
            }
        }
    }

    function _editData() {
        if(empty($this->Event->id)) {
            $this->request->data["Event"]["organizers"] = "[]";
            $this->request->data["Event"]["courses"] = "[]";
            return;
        }
        $conditions = array('Event.id' => $this->Event->id);
        $contain = array('Organizer.Role', 'Course', 'Organizer.User.name', 'Map', 'Series');
        $this->data = $this->Event->find('first', array('contain' => $contain, 'conditions' => $conditions));
        $courses = array();
        foreach($this->data["Course"] as $originalCourse) {
            $course = array();
            $course["id"] = $originalCourse["id"];
            $course["name"] = $originalCourse["name"];
            $course["distance"] = $originalCourse["distance"];
            $course["climb"] = $originalCourse["climb"];
            $course["description"] = $originalCourse["description"];

            array_push($courses, $course);
        }

        $organizers = array();
        // Organizer data to JSON
        foreach($this->data["Organizer"] as $originalOrganizer) {
            $organizer = array();
            $organizer["id"] = $originalOrganizer["user_id"];
            $organizer["name"] = $originalOrganizer["User"]["name"];
            $organizer["role"]["name"] = $originalOrganizer["Role"]["name"];
            $organizer["role"]["id"] = $originalOrganizer["Role"]["id"];

            array_push($organizers, $organizer);
        }

        $this->request->data["Event"]["organizers"] = json_encode($organizers);
        $this->request->data["Event"]["courses"] = json_encode($courses);
    }  

    function _timeFromParts($hours, $minutes, $seconds) {
        if(intval($hours) === 0 && intval($minutes) === 0 && intval($seconds) === 0) {
            return null;
        }

        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

        return $hours . ":" . $minutes . ":" . $seconds;
    }
}
?>
