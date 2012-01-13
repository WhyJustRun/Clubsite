<?php
/* Group Test cases generated on: 2011-03-10 05:03:48 : 1299733428*/
App::import('Model', 'Group');

class GroupTestCase extends CakeTestCase {
	var $fixtures = array('app.group', 'app.event', 'app.forum_topic', 'app.user', 'app.forum_message', 'app.membership', 'app.news', 'app.organizer', 'app.page', 'app.result');

	function startTest() {
		$this->Group =& ClassRegistry::init('Group');
	}

	function endTest() {
		unset($this->Group);
		ClassRegistry::flush();
	}

}
?>