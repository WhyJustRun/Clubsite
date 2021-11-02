<?php
class UseClubTask extends Shell {
    public $uses = array('Club');
    public function execute($id) {
        // Workaround for CakePHP not recreating the db connection if it times out on a long script run.
		$this->Club->getDatasource()->reconnect();

        useClubWithId($id);
    }
}
