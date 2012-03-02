<?php
class Privilege extends AppModel {
	var $name = 'Privilege';
	var $actsAs = array('Containable');
	protected $clubSpecific = false;
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	function beforeFind(&$queryData) {
        if($this->clubSpecific && empty($queryData['conditions'][$this->name.".club_id"])) {
            $queryData['conditions']["Group.club_id"] = Configure::read("Club.id");
        }
        return $queryData;
    }
}
