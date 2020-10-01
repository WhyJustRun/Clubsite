<?php
App::uses('AppModel', 'Model');

class EventClassification extends AppModel {

    public $displayField = 'name';
    public $clubSpecific = false;

    public $hasMany = array(
            'Event'
            );

    // Returns a formated list of all classifications in the form:
    // id => "name (description)"
    function getDescriptionList(){
        $classes = $this->Event->EventClassification->find('all');

        $list = array();
        foreach($classes as $class) {
            $id   = $class["EventClassification"]["id"];
            $name = $class["EventClassification"]["name"];
            $desc = $class["EventClassification"]["description"];
            $list[$id] = "$name ($desc)";
        }

        return $list;
    }

}
