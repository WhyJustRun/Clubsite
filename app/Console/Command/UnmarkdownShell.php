<?php
App::import('Vendor', 'Markdown');
class UnmarkdownShell extends Shell {
    var $uses = array('Event', 'ContentBlock', 'Page', 'Series');
    
    function convert($model, $field) {
        $modelName = get_class($model);
        $objects = $model->find('all', array('recursive' => -1, 'conditions' => array($modelName.'.club_id !=' => 0)));
        foreach($objects as $object) {
            if (!empty($object[$modelName][$field])) {
                $markdown = $object[$modelName][$field];
                $html = Markdown($markdown);
                $obj = $object[$modelName];
                $model->id = $obj['id'];
                $model->set('club_id', $obj['club_id']);
                $model->set($field, $html);
                $model->save();
            }
        }
    }
    
    function main() {
        $this->convert($this->Event, 'description');
        $this->convert($this->ContentBlock, 'content');
        $this->convert($this->Page, 'content');
        $this->convert($this->Series, 'description');
        $this->convert($this->Series, 'information');
    }
}
?>