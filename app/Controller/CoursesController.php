<?php
class CoursesController extends AppController {

    var $name = 'Courses';

    var $components = array(
        'RequestHandler',
        'Media' => array(
            'type' => 'Course',
            'allowedExts' => array('jpg', 'jpeg', 'gif', 'png', 'pdf'),
            'thumbnailSizes' => array('100x150', '600x600')
        )
    );

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('view', 'map');
    }

    function view($id) { 
        $course = $this->Course->findById($id); 
        if(isset($this->params['requested'])) { 
            return $course; 
        }

        $this->set('course', $course); 
    }

    function delete($id) {
        $course = $this->Course->find('first', array('conditions' => array('Course.id' => $id), 'contain' => array('Event.id')));
        $user = $this->Auth->user();
        if(!empty($course["Course"]["id"]) && $this->Course->Event->Organizer->isAuthorized($course["Event"]["id"], $user["id"])) {
            $this->Course->delete($course["Course"]["id"]);
        }
    }

    // Passing $userId = 0 will register the currently logged in user
    function register($courseId, $userId = 0) {
        $registrant = $this->Auth->user();
        if(empty($userId)) {
            $userId = $registrant['id'];
        }

        $user = $this->Course->Result->User->findById($userId);
        $course = $this->Course->Result->Course->findById($courseId);

        if(empty($course["Course"]["id"]) || empty($user['User']['id']) || empty($registrant['id'])) {
            $this->redirect("/");
        }

        // Make sure the user isn't registered already
        foreach($course["Result"] as $courseUser) {
            if($courseUser["user_id"] === $user['User']["id"]) {
                $this->redirect("/events/view/".$course["Event"]["id"]);
            }
        }

        // TODO-RWP Don't allow registration for events that already happened
        $registration["Result"]["registrant_id"] = $registrant['id'];
        $registration["Result"]["user_id"] = $user['User']["id"];
        $registration["Result"]["course_id"] = $course["Course"]["id"];
        $this->Course->Result->save($registration);

        $this->Session->setFlash("Registration successful!", "flash_success");
        $this->redirect("/events/view/".$course["Event"]["id"]);
    }



    // Can only unregister people you registered.
    function unregister($courseId, $userId = null) {
        $registrant = $this->Auth->user();
        if(empty($userId)) {
            $userId = $registrant['id'];
        }
        $course = $this->Course->findById($courseId);
        $registeredUsers = $this->Course->Result->find('all', array('recursive' => -1, 'conditions' => array('Result.registrant_id' => $registrant['id'])));
        $registeredUsersList = array();
        foreach($registeredUsers as $registeredUser) {
            array_push($registeredUsersList, $registeredUser['Result']['user_id']);
        }

        if($registrant['id'] != $userId && !in_array($userId, $registeredUsersList)) {
            $this->redirect('/');
        }

        $conditions = array();
        $conditions["Result.user_id"] = $userId;
        $conditions["Result.course_id"] = $courseId;
        $this->Course->Result->deleteAll($conditions);

        $this->Session->setFlash("Unregistration successful!", "flash_success");
        $this->redirect("/events/view/".$course["Event"]["id"]);
    }

    function uploadMap($id) {
        $course = $this->Course->findById($id);
        if (!$this->Course->Event->Organizer->isAuthorized($course['Event']['id'], AuthComponent::user('id'))) {
            $this->Session->setFlash("You aren't authorized to upload a map");
            $this->redirect('/events/view/'.$course['Event']['id']);
        }

        if ($this->request->is('post')) {
            $image = $this->request->data['Course']['image'];
            $error = null;
            if ($image['error'] != UPLOAD_ERR_OK) {
                $error = 'Error uploading map: please email support@whyjustrun.ca';
            } else if (empty($image['name'])) {
                $error = 'No file selected!';
            } else {
                $error = $this->Media->create($this->request->data['Course']['image'], $id);
            }
            
            if ($error) {
                $this->Session->setFlash($error);
            } else {
                $this->Session->setFlash('Course map uploaded!', 'flash_success');
            }

            $this->redirect('/events/uploadMaps/'.$course['Event']['id']);
        }
    }

    function map($id = null, $thumbnail = false) {
        if ($id !== null) {
            $this->Media->display($id, $thumbnail);
        } else {
            throw new NotFoundException('No course id specified');
        }
    }
}
