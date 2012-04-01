<?php
class MapStandardsController extends AppController {

    var $name = 'MapStandards';
    var $helpers = array("Form");
    var $components = array("RequestHandler");

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
    }


    function index() {
        $this->checkAuthorization(Configure::read('Privilege.MapStandard.edit'));
        $this->set('mapStandards', $this->MapStandard->find('all'));
    }
    function edit($id=null) {
        $this->checkAuthorization(Configure::read('Privilege.MapStandard.edit'));
        $this->MapStandard->id = $id;
        if (empty($this->data)) {
            $this->data = $this->MapStandard->read();
        } else {
            if ($this->MapStandard->save($this->data)) {
                $this->Session->setFlash('The map standard has been updated.', "flash_success");
                $this->redirect('/mapStandards/');
            }
        }
    }
    function delete() {
        $this->checkAuthorization(Configure::read('Privilege.MapStandard.delete'));
        if(!empty($this->data)) {
            if($this->MapStandard->delete($this->data["MapStandard"]["id"])){
                $this->Session->setFlash('The map standard has been deleted.', "flash_success");
            }
            else {
                $this->Session->setFlash('The map standard could not be deleted.');
            }
            $this->redirect('/mapStandards/');
        }
    }

}
?>
