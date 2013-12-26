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
    var $helpers = array("Time", "Geocode", "Form", "TimePlus", 'Leaflet', 'Session', 'Media');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'upcoming', 'past', 'major', 'view', 'rendering', 'planner', 'embed');
    }

    function index() {
        $this->checkAuthorization(Configure::read('Privilege.Official.edit'));
        $this->set('officials', $this->Official->find('all', array('order'=>'OfficialClassification.id')));
        $this->set('officialClassifications', $this->Official->OfficialClassification->find('list'));
        $this->set('users', $this->Official->User->find('list'));
    }

    function edit() {
        $this->checkAuthorization(Configure::read('Privilege.Official.edit'));
        if(!empty($this->data)) {
            if($this->Official->save($this->data)){
                $this->Session->setFlash('The official has been updated.', "flash_success");
            } else {
                $this->Session->setFlash('The official could not be updated.');
            }
            $this->redirect('/officials/');
        }
    }

    function add() {
        $this->checkAuthorization(Configure::read('Privilege.Official.edit'));
        if(!empty($this->data)) {
            if($this->Official->save($this->data)){
                $this->Session->setFlash('The official has been added.', "flash_success");
            } else {
                $this->Session->setFlash('The official could not be added.');
            }
            $this->redirect('/officials/');
        }
    }

    function delete() {
        $this->checkAuthorization(Configure::read('Privilege.Official.delete'));
        if(!empty($this->data)) {
            if($this->Official->delete($this->data["Official"]["id"])) {
                $this->Session->setFlash('The official was deleted.', 'flash_success');
            } else {
                $this->Session->setFlash('The official could not be deleted.');
            }
        } else {
            $this->Session->setFlash('No official id provided.');
        }
        $this->redirect("/officials/");
    }
}
