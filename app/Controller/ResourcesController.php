<?php
App::uses('AppController', 'Controller');
/**
 * Resources Controller
 *
 * @property Resource $Resource
 */
class ResourcesController extends AppController {
    public $helpers = array("Form", 'Session');

    function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->deny('*');
    }

    public function index() {
        $this->checkAuthorization(Configure::read('Privilege.Resource.index'));

        $resources = $this->Resource->findByClubId(Configure::read('Club.id'));

        $resourceKeys = array();
        $possibleResources = Configure::read('Resource.Club');
        // Just making the possible resource list work for the CakePHP form helper (need a hash map, not an array)
        foreach($possibleResources as $resourceKey => $options) {
            $resourceKeys[$resourceKey] = $options['name'];
        }

        $this->set('keys', $resourceKeys);
        $this->set('resources', $resources);
    }

    // Add a club resource
    public function add() {
        $this->checkAuthorization(Configure::read('Privilege.Resource.edit'));
        if(!$this->Resource->saveForClub(Configure::read('Club.id'), $this->request->data['Resource']['key'], $this->request->data['Resource']['file'], $this->request->data['Resource']['caption'])) {
            $this->Session->setFlash('Failed uploading resource.');    
        }
        $this->redirect('/resources/index');
    }

    // Delete a club resource
    public function delete($id) {
        $this->checkAuthorization(Configure::read('Privilege.Resource.delete'));
        $this->Resource->delete($id);
        $this->redirect('/resources/index');
    }
}
