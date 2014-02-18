<?php
// Dynamic robots.txt. Hidden for dev environments and clubs with visible = false
class RobotsController extends AppController {

    var $name = 'Robots';

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('view');
    }

    public function view() {
        $this->layout = null;
        $this->set('hidden', (Configure::read('debug') === 1 || Configure::read('Club.visible') === false));
    }
}
