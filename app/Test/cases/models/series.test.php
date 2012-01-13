<?php
/* Series Test cases generated on: 2011-06-04 19:21:00 : 1307215260*/
App::import('Model', 'Series');

class SeriesTestCase extends CakeTestCase {
	var $fixtures = array('app.series', 'app.club', 'app.event', 'app.group', 'app.forum_topic', 'app.user', 'app.forum_message', 'app.membership', 'app.news', 'app.organizer', 'app.page', 'app.result', 'app.map', 'app.map_standard', 'app.course');

	function startTest() {
		$this->Series =& ClassRegistry::init('Series');
	}

	function endTest() {
		unset($this->Series);
		ClassRegistry::flush();
	}

}
?>