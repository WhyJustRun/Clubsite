<?php
/* Resources Test cases generated on: 2012-01-03 01:19:13 : 1325553553*/
App::uses('ResourcesController', 'Controller');

/**
 * TestResourcesController *
 */
class TestResourcesController extends ResourcesController {
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
 * ResourcesController Test Case
 *
 */
class ResourcesControllerTestCase extends CakeTestCase {
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

		$this->Resources = new TestResourcesController();
		$this->Resources->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Resources);

		parent::tearDown();
	}

}
