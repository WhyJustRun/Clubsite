<?php
/* Club Test cases generated on: 2011-03-16 04:31:47 : 1300249907*/
App::import('Model', 'Club');

class ClubTestCase extends CakeTestCase {
	var $fixtures = array('app.club', 'app.event', 'app.map', 'app.map_standard', 'app.membership', 'app.news', 'app.page', 'app.series');

	function startTest() {
		$this->Club =& ClassRegistry::init('Club');
	}

	function endTest() {
		unset($this->Club);
		ClassRegistry::flush();
	}

}
?>