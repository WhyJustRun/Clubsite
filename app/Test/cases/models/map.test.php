<?php
/* Map Test cases generated on: 2011-03-16 04:22:05 : 1300249325*/
App::import('Model', 'Map');

class MapTestCase extends CakeTestCase {
	var $fixtures = array('app.map', 'app.club', 'app.map_standard', 'app.event');

	function startTest() {
		$this->Map =& ClassRegistry::init('Map');
	}

	function endTest() {
		unset($this->Map);
		ClassRegistry::flush();
	}

}
?>