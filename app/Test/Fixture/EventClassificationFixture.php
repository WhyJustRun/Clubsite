<?php
/* EventClassification Fixture generated on: 2012-03-04 02:03:59 : 1330826639 */

/**
 * EventClassificationFixture
 *
 */
class EventClassificationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'iof_3.0_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'iof_2.0.3_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'iof_3.0_name' => 'Lorem ipsum dolor sit amet',
			'iof_2.0.3_name' => 'Lorem ipsum dolor sit amet'
		),
	);
}
