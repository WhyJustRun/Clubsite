<?php
/* User Test cases generated on: 2011-03-10 04:56:54 : 1299733014*/
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $fixtures = array('app.user', 'app.group', 'app.forum_message', 'app.forum_topic', 'app.membership', 'app.news', 'app.organizer', 'app.page', 'app.result');

	function startTest() {
		$this->User =& ClassRegistry::init('User');
	}

	function endTest() {
		unset($this->User);
		ClassRegistry::flush();
	}

}
?>