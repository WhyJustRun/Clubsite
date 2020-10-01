<?php
class ResultList extends AppModel {
    var $name = 'ResultList';
    var $actsAs = array('Containable');
    protected $clubSpecific = false;

    var $belongsTo = array('Event');

    public function findLiveResultByEventId($id) {
        $conditions = array('event_id' => $id, 'status' => 'live');
        return $this->find('first', array('conditions' => $conditions));
    }
}
