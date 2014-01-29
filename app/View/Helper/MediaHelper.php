<?php
class MediaHelper extends Helper {
    public $helpers = array('Html');
    private $endpoints = array(
        'Course' => '/courses/map/',
        'Map' => '/maps/rendering/',
        'Result' => '/events/rendering/',
    );

    public function image($type, $id, $thumbnail = false, $options = array()) {
        $options['srcset'] = $this->url($type, $id, $thumbnail) . ' 1x, ' . $this->url($type, $id, $thumbnail, true) . " 2x";
        return $this->Html->image($this->url($type, $id, $thumbnail), $options);
    }

    public function linkedImage($type, $id, $thumbnail = false, $options = array(), $imageOptions = array()) {
        $options['escape'] = false;
        return $this->Html->link(
            $this->image($type, $id, $thumbnail, $imageOptions),
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

    public function url($type, $id, $thumbnail = false, $hiDPI = false) {
        if (empty($id)) {
            throw new Exception("No media resource ID provided to build URL.");
        }
        if ($hiDPI && $thumbnail) {
            $glue = "x";
            $components = explode($glue, $thumbnail);
            $newComponents = array();
            foreach($components as $component) {
                $newComponents[] = intval($component) * 2;
                    }

                    $thumbnail = implode($glue, $newComponents);
        }

        $endpoint = $this->endpoint($type);
        if($thumbnail) {
            return $endpoint . $id . '/' . $thumbnail;
        }
        return $endpoint . $id;
    }

    public function exists($type, $id, $thumbnail = false) {
        if(!$thumbnail) {
            $matches = glob(Configure::read("$type.dir").$id.".*");
        } else {
            $matches = glob(Configure::read("$type.dir")."${id}_${thumbnail}.*");
        }
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
