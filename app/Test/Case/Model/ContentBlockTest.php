<?php
/* ContentBlock Test cases generated on: 2011-11-14 04:58:23 : 1321246703*/
App::uses('ContentBlock', 'Model');

/**
 * ContentBlock Test Case
 *
 */
class ContentBlockTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.content_block');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->ContentBlock = ClassRegistry::init('ContentBlock');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ContentBlock);

		parent::tearDown();
	}

}
