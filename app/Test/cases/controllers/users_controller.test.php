<?php
/* Users Test cases generated on: 2011-03-10 04:59:21 : 1299733161*/
App::import('Controller', 'Users');

class TestUsersController extends UsersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class UsersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.group', 'app.forum_message', 'app.forum_topic', 'app.membership', 'app.news', 'app.organizer', 'app.page', 'app.result');

	function startTest() {
		$this->Users =& new TestUsersController();
		$this->Users->constructClasses();
	}

	function endTest() {
		unset($this->Users);
		ClassRegistry::flush();
	}

}
?>