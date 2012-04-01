<?php
class ClubsController extends AppController {

    var $name = 'Clubs';

    function beforeFilter() {
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
}
?>
