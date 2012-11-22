<?php
class ClubsController extends AppController {

    var $name = 'Clubs';
    var $helpers = array("Form");
    var $components = array('Facebook', 'Auth', 'Session');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }

    function index() {
        $clubs = $this->Club->find('all', array('conditions' => array('Club.visible' => 1), 'recursive' => -1));

        if (!empty($this->params['requested'])) {
            return $clubs;
        } else {
            $this->set('clubs', $clubs);
        }
    }
    
    function edit() {
        $this->checkAuthorization(Configure::read('Privilege.Club.edit'));
        $this->set('title_for_layout', 'Edit Club Information');
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data['Club']['facebook_page_id'] = $this->Facebook->transformPageURLToID($data['Club']['facebook_page_id']);
            $this->Club->id = Configure::read('Club.id');
            if ($this->Club->save($data)) {
                $this->Session->setFlash('Updated club');
                $this->redirect('/pages/admin');
            }
        }
        
        $club = $this->Club->find('first', array('recursive' => -1, 'conditions' => array('Club.id' => Configure::read('Club.id'))));
        $club['Club']['facebook_page_id'] = $this->Facebook->transformPageIDToURL($club['Club']['facebook_page_id']);
        $this->data = $club;
        $this->set('clubs', $this->Club->find('list', array('order' => 'Club.name')));
        $this->set('clubCategories', $this->Club->ClubCategory->find('list'));
    }
}
?>
