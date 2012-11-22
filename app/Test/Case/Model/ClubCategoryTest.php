<?php
App::uses('ClubCategory', 'Model');

/**
 * ClubCategory Test Case
 *
 */
class ClubCategoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.club_category',
		'app.club',
		'app.user',
		'app.privilege',
		'app.group',
		'app.membership',
		'app.organizer',
		'app.event',
		'app.map',
		'app.map_standard',
		'app.resource',
		'app.course',
		'app.result',
		'app.series',
		'app.event_classification',
		'app.role'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ClubCategory = ClassRegistry::init('ClubCategory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ClubCategory);

		parent::tearDown();
	}

}
