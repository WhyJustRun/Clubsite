<?php
class MapStandard extends AppModel {
    var $name = 'MapStandard';
    var $displayField = 'name';
    var $actsAs = array('Containable');
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $clubSpecific = false; // shared between clubs
    var $hasMany = array(
            'Map' => array(
                'className' => 'Map',
                'foreignKey' => 'map_standard_id',
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
