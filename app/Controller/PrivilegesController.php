<?php
class PrivilegesController extends AppController {

	var $name = 'Privileges';
	var $helpers = array("Form");
	var $components = array('Email', 'RequestHandler');

	function beforeFilter()
	{
		$this->Auth->allow('register', 'forgot', 'login', 'verify', 'authorized');
		parent::beforeFilter();
	}

	function index() {
	    $this->checkAuthorization(Configure::read('Privilege.Privilege.edit'));
      $this->loadModel('Group');
		$this->set('groups', $this->Privilege->Group->find('list'));
		$this->set('users', $this->Privilege->User->find('list'));
      $this->set('privileges', $this->Privilege->find('all'));

      $this->Group->unbindModel( array('hasOne' => array('Privilege')));
		$this->set('groupsList', $this->Group->find('all'));
		//$this->_checkEditAuthorization($id);
   }
   function edit() {
        $this->checkAuthorization(Configure::read('Privilege.Privilege.edit'));
      if(!empty($this->data)) {
         if($this->Privilege->save($this->data)){
				$this->Session->setFlash('The privilege has been updated.', "flash_success");
         }
         else {
				$this->Session->setFlash('The privilege could not be updated.');
         }
         $this->redirect('/Privileges/');
      }
	}
   function add() {
      if(!empty($this->data)) {
         if($this->Privilege->save($this->data)){
				$this->Session->setFlash('The privilege has been added.', "flash_success");
         }
         else {
				$this->Session->setFlash('The privilege could not be added.');
         }
         $this->redirect('/Privileges/');
      }
	}
   function delete() {
        $this->checkAuthorization(Configure::read('Privilege.Privilege.edit'));
      if(!empty($this->data)) {
         if($this->Privilege->delete($this->data["Privilege"]["id"])){
				$this->Session->setFlash('The privilege has been deleted.', "flash_success");
         }
         else {
				$this->Session->setFlash('The privilege could not be deleted.');
         }
         $this->redirect('/Privileges/');
      }
	}

}
