<?php
/* Events Test cases generated on: 2011-03-16 04:49:51 : 1300250991*/
App::import('Controller', 'Events');

class TestEventsController extends EventsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class EventsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.event', 'app.group', 'app.forum_topic', 'app.user', 'app.forum_message', 'app.membership', 'app.news', 'app.organizer', 'app.page', 'app.result', 'app.club', 'app.map', 'app.map_standard', 'app.series', 'app.course');

	function startTest() {
		$this->Events =& new TestEventsController();
		$this->Events->constructClasses();
	}

	function endTest() {
		unset($this->Events);
		ClassRegistry::flush();
	}

}
?>