<?php
/* Privilege Test cases generated on: 2011-08-26 03:33:32 : 1314329612*/
App::import('Model', 'Privilege');

class PrivilegeTestCase extends CakeTestCase {
	var $fixtures = array('app.privilege', 'app.group', 'app.event', 'app.map', 'app.map_standard', 'app.series', 'app.course', 'app.result', 'app.user', 'app.forum_message', 'app.forum_topic', 'app.membership', 'app.news', 'app.organizer', 'app.role');

	function startTest() {
		$this->Privilege =& ClassRegistry::init('Privilege');
	}

	function endTest() {
		unset($this->Privilege);
		ClassRegistry::flush();
	}

}
