<?php
/* MapStandard Test cases generated on: 2011-03-14 04:53:25 : 1300078405*/
App::import('Model', 'MapStandard');

class MapStandardTestCase extends CakeTestCase {
	var $fixtures = array('app.map_standard', 'app.map');

	function startTest() {
		$this->MapStandard =& ClassRegistry::init('MapStandard');
	}

	function endTest() {
		unset($this->MapStandard);
		ClassRegistry::flush();
	}

}
?>