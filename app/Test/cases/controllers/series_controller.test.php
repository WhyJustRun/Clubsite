<?php
/* Series Test cases generated on: 2011-06-04 19:21:54 : 1307215314*/
App::import('Controller', 'Series');

class TestSeriesController extends SeriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SeriesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.series', 'app.club', 'app.event', 'app.group', 'app.forum_topic', 'app.user', 'app.forum_message', 'app.membership', 'app.news', 'app.organizer', 'app.page', 'app.result', 'app.map', 'app.map_standard', 'app.course');

	function startTest() {
		$this->Series =& new TestSeriesController();
		$this->Series->constructClasses();
	}

	function endTest() {
		unset($this->Series);
		ClassRegistry::flush();
	}

}
?>