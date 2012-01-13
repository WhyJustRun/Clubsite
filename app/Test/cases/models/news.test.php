<?php
/* News Test cases generated on: 2011-07-25 02:54:02 : 1311562442*/
App::import('Model', 'News');

class NewsTestCase extends CakeTestCase {
	var $fixtures = array('app.news', 'app.user', 'app.group', 'app.event', 'app.map', 'app.map_standard', 'app.series', 'app.course', 'app.result', 'app.organizer', 'app.role', 'app.forum_topic', 'app.forum_message', 'app.membership');

	function startTest() {
		$this->News =& ClassRegistry::init('News');
	}

	function endTest() {
		unset($this->News);
		ClassRegistry::flush();
	}

}
