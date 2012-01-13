<?php
/* Result Test cases generated on: 2011-06-05 02:37:14 : 1307241434*/
App::import('Model', 'Result');

class ResultTestCase extends CakeTestCase {
	var $fixtures = array('app.result', 'app.user', 'app.group', 'app.event', 'app.club', 'app.map', 'app.map_standard', 'app.membership', 'app.news', 'app.page', 'app.series', 'app.course', 'app.organizer', 'app.forum_topic', 'app.forum_message');

	function startTest() {
		$this->Result =& ClassRegistry::init('Result');
	}

	function endTest() {
		unset($this->Result);
		ClassRegistry::flush();
	}

}
?>