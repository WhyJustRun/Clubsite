<?php
class ClubIDsTask extends Shell {
    public $uses = array('Club');
    public function execute() {
        return array_keys($this->Club->find('list', array('fields' => array())));
    }
}
