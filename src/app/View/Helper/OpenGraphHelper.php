<?php
App::uses('AppHelper', 'View/Helper');

class OpenGraphHelper extends AppHelper {
    public $helpers = array('Html');

    public function addTag($property, $value) {
        $attributes = array('property' => $property, 'content' => $value);
        $html = $this->Html->tag('meta', null, $attributes);
        // This appends the tag to a block which is outputted in the head tag (see the layout_dependencies element).
        $this->_View->append('open_graph', $html);
    }
}
