<?php
class RolesController extends AppController {

    var $name = 'Roles';
    var $helpers = array("Form");
    var $components = array("RequestHandler");

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('*');
    }

    // Used for the roles list, and for a JSON request by the Edit Events page (regular users need access)
    function index() {
        $this->set('roles', $this->Role->find('all'));
    }

    function edit($id=null) {
        $this->checkAuthorization(Configure::read('Privilege.Role.edit'));
        $this->Role->id = $id;
        if (empty($this->data)) {
            $this->data = $this->Role->read();
        } else {
            if ($this->Role->save($this->data)) {
                $this->Session->setFlash('The role has been updated.', "flash_success");
                //$this->redirect('/Series/view/'.$this->Series->id);
                $this->redirect('/roles/');
            }
        }
    }
    /*
       We don't want dangling references to deleted roles
       function delete() {
       $this->checkAuthorization(Configure::read('Privilege.Role.delete'));
       if(!empty($this->data)) {
       if($this->Role->delete($this->data["Role"]["id"])){
       $this->Session->setFlash('The role has been deleted.', "flash_success");
       }
       else {
       $this->Session->setFlash('The role could not be deleted.');
       }
       $this->redirect('/roles/');
       }
       }
     */
}
