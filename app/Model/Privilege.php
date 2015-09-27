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

    var $validate = array(
            'user_id' => array(
                'required' => array(
                    'rule' => array('notBlank'),
                    'required' => true,
                    ),
                ),
            'group_id' => array(
                'required' => array(
                    'rule' => array('notBlank'),
                    'required' => true,
                    ),
                ),
            );

    // For privileges, we just want to make sure that privileges in groups in the current club are the only ones found
    function beforeFind($queryData) {
        if(empty($queryData['conditions']["Group.club_id"])) {
            $queryData['conditions']["Group.club_id"] = Configure::read("Club.id");
        }
        return $queryData;
    }
}
