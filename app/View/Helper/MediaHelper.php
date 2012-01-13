<?php
class MediaHelper extends Helper {
    public $helpers = array('Html');
    private $endpoints = array(
        'Course' => '/courses/map/',
        'Map' => '/maps/rendering/',
        'Result' => '/events/rendering/',
    );
    
    public function image($type, $id, $thumbnail = false) {
        return $this->Html->image($this->url($type, $id, $thumbnail));
    }
    
    public function linkedImage($type, $id, $thumbnail = false, $options = array()) {
        $options['escape'] = false;
        return $this->Html->link(
            $this->image($type, $id, $thumbnail),
            $this->url($type, $id),
            $options
        );
    }

    public function linkedFile($type, $id, $options = array()) {
        $options['escape'] = false;
        return $this->Html->link(
            "Results",
            $this->url($type, $id),
            $options
        );
    }
    
    public function url($type, $id, $thumbnail = false) {
        $endpoint = $this->endpoint($type);
        if($thumbnail) {
            return $endpoint . $id . '/' . $thumbnail;
        }
        return $endpoint . $id;
    }
    
    public function exists($type, $id) {
        $matches = glob(Configure::read("$type.dir").$id.".*");
        if(count($matches) == 1) {
            return true;
        } else if(count($matches) == 0) {
            return false;
        } else {
            throw new Exception('Multiple matches for media resource.');
        }
    }
    
    private function endpoint($type) {
        $endpoint = $this->endpoints[$type];
        if(!$endpoint) {
            throw new Exception('No endpoint found');
        }
        
        return $endpoint;
    }
}
?>
