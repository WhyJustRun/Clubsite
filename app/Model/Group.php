<?php
class Group extends AppModel {
    var $name = 'Group';
    var $displayField = 'name';
    var $actsAs = array('Containable');
    var $clubSpecific = true;
    var $validate = array(
            'name' => array(
                'alphanumeric' => array(
                    'rule' => array('alphanumeric'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
                    ),
                ),
            'access_level' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
                    ),
                ),
            'description' => array(
                    'alphanumeric' => array(
                        'rule' => array('alphanumeric'),
                        //'message' => 'Your custom message here',
                        //'allowEmpty' => false,
                        //'required' => false,
                        //'last' => false, // Stop validation after this rule
                        //'on' => 'create', // Limit validation to 'create' or 'update' operations
                        ),
                    ),
            );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $hasOne = array('Privilege');

}
?>
