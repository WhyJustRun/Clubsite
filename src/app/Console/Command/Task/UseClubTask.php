<?php
class UseClubTask extends Shell {
    public $uses = array('Club');
    public function execute($id) {
        useClubWithId($id);
    }
}
