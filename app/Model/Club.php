<?php
class Club extends AppModel {
    var $name = 'Club';
    var $displayField = 'name';
    var $clubSpecific = false;
    var $actsAs = array('Containable');
    var $validate = array(
        'parent_id' => array(
            'rule' => 'checkForCycles',
            'message' => 'Invalid parent organisation: would introduce a cycle',
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
        'lat' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'lng' => array(
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
    
    var $belongsTo = array('ClubCategory');
    var $hasMany = array('User', 'Resource');
    
    function checkForCycles($check) {
        $id = $this->id;
        if ($id === $check['parent_id']) return false;
        if ($id === NULL) return true;
        $parentID = $check['parent_id'];
        while ($parentID != NULL) {
            $club = $this->findById($parentID);
            if ($club['Club']['id'] === $id) {
                return false;
            }
            $parentID = $club['Club']['parent_id'];
        }
        
        return true;
    }
}
?>
