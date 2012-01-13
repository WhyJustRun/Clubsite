<?php
/* ForumMessage Fixture generated on: 2011-07-25 02:46:59 : 1311562019 */
class ForumMessageFixture extends CakeTestFixture {
	var $name = 'ForumMessage';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'forum_topic_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'forum_topic_id' => 1,
			'user_id' => 1
		),
	);
}
