<?php
class OfficialsController extends AppController {

    var $name = 'Officials';

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

    function index() {
        $this->set('officials', $this->Official->find('all', array('order'=>'OfficialClassification.id')));
    }

    function edit($id = null) {
        // Check permission
        //$edit = $this->isAuthorized(Configure::read('Privilege.Official.edit'));
        $edit = 1;
        if(!$edit) {
            if($id == null) {
                $this->Session->setFlash('You are not authorized to add an official.');
                $this->redirect('/officials/');
            } else {
                $this->Session->setFlash('You are not authorized to edit this official.');
                $this->redirect('/official/index/');
            }
        }

        if($id != null)
            $this->set('official', $this->Official->findById($id));
        $this->set('officialClassifications', $this->Official->OfficialClassification->find('list'));
        $this->set('users', $this->Official->User->find('list'));

        $this->Official->id = $id;
        if (empty($this->data)) {
            $this->data = $this->Official->read();
        } else {
            if ($this->Official->save($this->data)) {
                $this->Session->setFlash('The official has been updated.', "flash_success");
                $this->redirect('/officials/');
            }
        }
    }

    function delete() {
        $this->checkAuthorization(Configure::read('Privilege.Official.delete'));
        if(!empty($this->data)) {
           $this->Official->delete($this->data["Official"]["id"]);
            $this->Session->setFlash('The official was deleted.', 'flash_success');
        }
        else {
            $this->Session->setFlash('No official id provided.');
        }
        $this->redirect("/officials/");
    }
}
?>
