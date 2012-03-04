<?php
/* EventClassification Test cases generated on: 2012-03-04 02:03:59 : 1330826639*/
App::uses('EventClassification', 'Model');

/**
 * EventClassification Test Case
 *
 */
class EventClassificationTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.event_classification', 'app.event', 'app.group', 'app.privilege', 'app.user', 'app.club', 'app.resource', 'app.course', 'app.result', 'app.map', 'app.map_standard', 'app.membership', 'app.organizer', 'app.role', 'app.series');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->EventClassification = ClassRegistry::init('EventClassification');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EventClassification);

		parent::tearDown();
	}

}
