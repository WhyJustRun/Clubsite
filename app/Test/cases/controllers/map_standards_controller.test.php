<?php
/* MapStandards Test cases generated on: 2011-03-14 04:53:51 : 1300078431*/
App::import('Controller', 'MapStandards');

class TestMapStandardsController extends MapStandardsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class MapStandardsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.map_standard', 'app.map');

	function startTest() {
		$this->MapStandards =& new TestMapStandardsController();
		$this->MapStandards->constructClasses();
	}

	function endTest() {
		unset($this->MapStandards);
		ClassRegistry::flush();
	}

}
?>