<?php
/* Privileges Test cases generated on: 2011-08-26 03:33:45 : 1314329625*/
App::import('Controller', 'Privileges');

class TestPrivilegesController extends PrivilegesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class PrivilegesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.privilege', 'app.group', 'app.event', 'app.map', 'app.map_standard', 'app.series', 'app.course', 'app.result', 'app.user', 'app.forum_message', 'app.forum_topic', 'app.membership', 'app.news', 'app.organizer', 'app.role');

	function startTest() {
		$this->Privileges =& new TestPrivilegesController();
		$this->Privileges->constructClasses();
	}

	function endTest() {
		unset($this->Privileges);
		ClassRegistry::flush();
	}

}
