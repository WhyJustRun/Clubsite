<?php
/* Result Fixture generated on: 2011-06-05 02:37:14 : 1307241434 */
class ResultFixture extends CakeTestFixture {
	var $name = 'Result';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'course_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'time' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'non_competitive' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'points' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'needs_ride' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'offering_ride' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array()
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'course_id' => 1,
			'time' => '02:37:14',
			'non_competitive' => 1,
			'points' => 1,
			'needs_ride' => 1,
			'offering_ride' => 1
		),
	);
}
?>