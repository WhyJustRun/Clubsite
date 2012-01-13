<?php
/* Course Test cases generated on: 2011-06-04 19:23:33 : 1307215413*/
App::import('Model', 'Course');

class CourseTestCase extends CakeTestCase {
	var $fixtures = array('app.course', 'app.event', 'app.group', 'app.forum_topic', 'app.user', 'app.forum_message', 'app.membership', 'app.news', 'app.organizer', 'app.page', 'app.result', 'app.club', 'app.map', 'app.map_standard', 'app.series');

	function startTest() {
		$this->Course =& ClassRegistry::init('Course');
	}

	function endTest() {
		unset($this->Course);
		ClassRegistry::flush();
	}

}
?>