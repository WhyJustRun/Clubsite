<?php
/* Event Fixture generated on: 2011-03-16 04:49:16 : 1300250956 */
class EventFixture extends CakeTestFixture {
	var $name = 'Event';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'club_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'map_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'series_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'time' => array('type' => 'time', 'null' => true, 'default' => NULL),
		'lat' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'lng' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'is_ranked' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 4),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array()
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'group_id' => 1,
			'club_id' => 1,
			'map_id' => 1,
			'series_id' => 1,
			'date' => '2011-03-16',
			'time' => '04:49:16',
			'lat' => 1,
			'lng' => 1,
			'is_ranked' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
		),
	);
}
?>