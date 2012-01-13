<?php
/* Map Fixture generated on: 2011-03-16 04:22:05 : 1300249325 */
class MapFixture extends CakeTestFixture {
	var $name = 'Map';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'club_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'map_standard_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'scale' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lat' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'lng' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'club_id' => 1,
			'map_standard_id' => 1,
			'created' => '2011-03-16 04:22:05',
			'modified' => '2011-03-16 04:22:05',
			'scale' => 'Lorem ipsum dolor sit amet',
			'lat' => 1,
			'lng' => 1
		),
	);
}
?>