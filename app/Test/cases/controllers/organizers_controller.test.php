<?php
/* Organizers Test cases generated on: 2011-06-05 17:30:51 : 1307295051*/
App::import('Controller', 'Organizers');

class TestOrganizersController extends OrganizersController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class OrganizersControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.organizer', 'app.user', 'app.group', 'app.event', 'app.club', 'app.map', 'app.map_standard', 'app.membership', 'app.news', 'app.page', 'app.series', 'app.course', 'app.result', 'app.forum_topic', 'app.forum_message', 'app.role');

	function startTest() {
		$this->Organizers =& new TestOrganizersController();
		$this->Organizers->constructClasses();
	}

	function endTest() {
		unset($this->Organizers);
		ClassRegistry::flush();
	}

}
?>