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

        $error = 'Failed uploading resource.';
        $clubId = Configure::read('Club.id');
        $key = $this->request->data['Resource']['key'];
        $file = $this->request->data['Resource']['file'];
        $caption = $this->request->data['Resource']['caption'];
        if(!$this->Resource->saveForClub($clubId, $key, $file, $caption, $error)) {
            $this->Session->setFlash($error);
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
