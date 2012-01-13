<?php
/* Organizer Fixture generated on: 2011-06-05 17:30:05 : 1307295005 */
class OrganizerFixture extends CakeTestFixture {
	var $name = 'Organizer';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'event_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'role_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array()
	);

	var $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'event_id' => 1,
			'role_id' => 1
		),
	);
}
?>