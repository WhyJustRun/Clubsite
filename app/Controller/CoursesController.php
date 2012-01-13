<?php
class CoursesController extends AppController {

	var $name = 'Courses';

	var $components = array(
	   'RequestHandler',
	   'Media' => array(
	       'type' => 'Course',
	       'allowedExts' => array('jpg', 'jpeg', 'gif', 'png'),
	       'thumbnailSizes' => array('100x150')
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
	
	function register($id) {
		$user = $this->Auth->user();
		$course = $this->Course->findById($id);
		
		if(empty($course["Course"]["id"])) {
			$this->redirect("/");
		}
		

		// Make sure the user isn't registered already
		foreach($course["Result"] as $courseUser) {
			if($courseUser["user_id"] === $user["id"]) {
				$this->redirect("/Events/view/".$course["Event"]["id"]);
			}
		}
		
		// TODO-RWP Don't allow registration for events already happened
		$registration["Result"]["user_id"] = $user["id"];
		$registration["Result"]["course_id"] = $course["Course"]["id"];
		$this->Course->Result->save($registration);
		
		$this->Session->setFlash("You are now registered!", "flash_success");
		$this->redirect("/Events/view/".$course["Event"]["id"]);
	}
	
	function uploadMap($id) {
	   $course = $this->Course->findById($id);
	   if(!$this->Course->Event->Organizer->isAuthorized($course['Event']['id'], AuthComponent::user('id'))) {
	       $this->Session->setFlash("You aren't authorized to upload a map");
	       $this->redirect('/events/view/'.$course['Event']['id']);
	   }
	   
	   if($this->request->is('post')) {
           if($this->request->data['Course']['image']['name'] != "") {
    	       $this->Media->create($this->request->data['Course']['image'], $id);
           }
           else {
               $this->Session->setFlash('No file selected!');
               $this->redirect('/events/uploadMaps/'.$course['Event']['id']);
           }
	   }
	   
	   $this->Session->setFlash('Course map uploaded!', 'flash_success');
	   $this->redirect('/events/uploadMaps/'.$course['Event']['id']);
	}
	
	function map($id, $thumbnail = false) {
	   $this->Media->display($id, $thumbnail);
	}
	
	function unregister($id) {
		$user = $this->Auth->user();
		$course = $this->Course->findById($id);
		
		$conditions = array();
		$conditions["Result.user_id"] = $user["id"];
		$conditions["Result.course_id"] = $id;
		$this->Course->Result->deleteAll($conditions);
		
		$this->Session->setFlash("You are now unregistered", "flash_success");
		$this->redirect("/Events/view/".$course["Event"]["id"]);
	}
}
?>
