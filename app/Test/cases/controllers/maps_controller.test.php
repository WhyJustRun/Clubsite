<?php
/* Maps Test cases generated on: 2011-03-16 04:23:55 : 1300249435*/
App::import('Controller', 'Maps');

class TestMapsController extends MapsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class MapsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.map', 'app.club', 'app.map_standard', 'app.event');

	function startTest() {
		$this->Maps =& new TestMapsController();
		$this->Maps->constructClasses();
	}

	function endTest() {
		unset($this->Maps);
		ClassRegistry::flush();
	}

}
?>