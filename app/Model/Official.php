<?php
class Official extends AppModel {
    var $name = 'Official';
    var $actsAs = array('Containable');
    protected $clubSpecific = false; // associated with an event which is club specific

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
            'user_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    //'message' => 'Your custom message here',
                    //'allowEmpty' => false,
                    //'required' => false,
                    //'last' => false, // Stop validation after this rule
                    //'on' => 'create', // Limit validation to 'create' or 'update' operations
                    ),
                ),
            'official_classification_id' => array(
                    'numeric' => array(
                        'rule' => array('numeric'),
                        //'message' => 'Your custom message here',
                        //'allowEmpty' => false,
                        //'required' => false,
                        //'last' => false, // Stop validation after this rule
                        //'on' => 'create', // Limit validation to 'create' or 'update' operations
                        ),
                    ),
            );
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $belongsTo = array('User', 'OfficialClassification');

}
