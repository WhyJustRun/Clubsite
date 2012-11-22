<?php
App::uses('AppModel', 'Model');
/**
 * ClubCategory Model
 *
 * @property Club $Club
 */
class ClubCategory extends AppModel {
    var $clubSpecific = false;
    public $displayField = 'name';
    
    public $validate = array(
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
    );

    public $hasMany = array(
        'Club' => array(
            'className' => 'Club',
            'foreignKey' => 'club_category_id',
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
