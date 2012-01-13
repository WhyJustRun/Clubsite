<?php
/* Clubs Test cases generated on: 2011-03-16 04:33:44 : 1300250024*/
App::import('Controller', 'Clubs');

class TestClubsController extends ClubsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ClubsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.club', 'app.event', 'app.map', 'app.map_standard', 'app.membership', 'app.news', 'app.page', 'app.series');

	function startTest() {
		$this->Clubs =& new TestClubsController();
		$this->Clubs->constructClasses();
	}

	function endTest() {
		unset($this->Clubs);
		ClassRegistry::flush();
	}

}
?>