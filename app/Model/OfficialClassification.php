<?php
App::uses('AppModel', 'Model');

class OfficialClassification extends AppModel {

    public $displayField = 'name';
    public $clubSpecific = false;

    public $hasMany = array(
            'Official'
            );
}
