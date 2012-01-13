<?php
App::import('Helper', 'TimePlus');
App::import('Helper', 'Time');

class TimePlusTest extends CakeTestCase {
	private $timePlus = null;

	public function startTest() {
		$this->timePlus = new TimePlusHelper();
		$this->timePlus->Time = new TimeHelper();
	}

	public function testNice() {
		$this->assertEqual('Mon, Aug 15th 2005, 08:52', $this->timePlus->nice("2005-08-15T15:52:01+00:00"));
	}

	public function testNiceShort() {
		$now = new DateTime();
		$this->assertEqual('Mon, Aug 15th 2005, 08:52', $this->timePlus->niceShort($now->getTimestamp()));
	}

	public function testFormat() {
		$this->assertEqual('Mon, Aug 15th 2005, 08:52', $this->timePlus->format('d-m-y', "2005-08-15T15:52:01+00:00"));
	}

}
?>