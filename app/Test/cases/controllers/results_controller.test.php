<?php
/* Results Test cases generated on: 2011-06-05 02:37:38 : 1307241458*/
App::import('Controller', 'Results');

class TestResultsController extends ResultsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class ResultsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.result', 'app.user', 'app.group', 'app.event', 'app.club', 'app.map', 'app.map_standard', 'app.membership', 'app.news', 'app.page', 'app.series', 'app.course', 'app.organizer', 'app.forum_topic', 'app.forum_message');

	function startTest() {
		$this->Results =& new TestResultsController();
		$this->Results->constructClasses();
	}

	function endTest() {
		unset($this->Results);
		ClassRegistry::flush();
	}

}
?>