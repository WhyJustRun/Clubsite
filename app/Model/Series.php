<?php
class Series extends AppModel {
    var $name = 'Series';
    var $displayField = 'name';
    var $actsAs = array('Containable');
    var $validate = array(
            'id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
                    ),
                ),
            'acronym' => array(
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

    var $hasMany = array(
            'Event' => array(
                'className' => 'Event',
                'foreignKey' => 'series_id',
                'dependent' => false,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
                )
            );
}
