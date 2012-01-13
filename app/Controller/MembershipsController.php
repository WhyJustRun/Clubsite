<?php
class MembershipsController extends AppController {

	var $name = 'Memberships';
	var $helpers = array("Form");
	var $components = array("RequestHandler");

   function beforeFilter() {
        parent::beforeFilter();
		$this->Auth->deny('*');
	}
	
	function index() {
        $this->loadModel('User');
        $this->set('users', $this->User->find('list', array('order'=>'name')));
        $this->set('memberships', $this->Membership->find('all',array('order'=> array('year'=>'desc'))));
	}
	
    function edit($id=null) {
        $this->checkAuthorization(Configure::read('Privilege.Membership.edit'));
		$this->Membership->id = $id;
		if (empty($this->data)) {
            $this->loadModel('User');
            $this->set('users', $this->User->find('list', array('order'=>'name')));
			$this->data = $this->Membership->read();
		} else {
			if ($this->Membership->save($this->data)) {
				$this->Session->setFlash('The membership has been updated.', "flash_success");
				$this->redirect('/memberships/');
			}
            else {
				$this->Session->setFlash('The membership could not be updated.');
            }
		}
    }
    function delete() {
        $this->checkAuthorization(Configure::read('Privilege.Membership.delete'));
        if(!empty($this->data)) {
            if($this->Membership->delete($this->data["Membership"]["id"])){
                $this->Session->setFlash('The membership has been deleted.', "flash_success");
            }
            else {
                $this->Session->setFlash('The membership could not be deleted.');
            }
            $this->redirect('/memberships/');
        }
    }
}
?>
