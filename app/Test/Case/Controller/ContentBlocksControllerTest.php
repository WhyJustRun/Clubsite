<?php
/* ContentBlocks Test cases generated on: 2011-11-14 04:59:01 : 1321246741*/
App::uses('ContentBlocksController', 'Controller');

/**
 * TestContentBlocksController *
 */
class TestContentBlocksController extends ContentBlocksController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * ContentBlocksController Test Case
 *
 */
class ContentBlocksControllerTestCase extends CakeTestCase {
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

		$this->ContentBlocks = new TestContentBlocksController();
		$this->ContentBlocks->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ContentBlocks);

		parent::tearDown();
	}

}
