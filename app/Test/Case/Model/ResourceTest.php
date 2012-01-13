<?php
/* Resource Test cases generated on: 2012-01-03 01:16:11 : 1325553371*/
App::uses('Resource', 'Model');

/**
 * Resource Test Case
 *
 */
class ResourceTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.resource', 'app.club', 'app.user', 'app.privilege', 'app.group', 'app.event', 'app.map', 'app.map_standard', 'app.series', 'app.course', 'app.result', 'app.organizer', 'app.role', 'app.membership');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Resource = ClassRegistry::init('Resource');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Resource);

		parent::tearDown();
	}

}
