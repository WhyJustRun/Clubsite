<?php
/* Organizer Test cases generated on: 2011-06-05 17:30:05 : 1307295005*/
App::import('Model', 'Organizer');

class OrganizerTestCase extends CakeTestCase {
	var $fixtures = array('app.organizer', 'app.user', 'app.group', 'app.event', 'app.club', 'app.map', 'app.map_standard', 'app.membership', 'app.news', 'app.page', 'app.series', 'app.course', 'app.result', 'app.forum_topic', 'app.forum_message', 'app.role');

	function startTest() {
		$this->Organizer =& ClassRegistry::init('Organizer');
	}

	function endTest() {
		unset($this->Organizer);
		ClassRegistry::flush();
	}

}
?>