<?php
// There is a small performance cost on requestActions, so avoid if possible.
if($this->Session->check('Auth.User.id')) {
    if($this->Session->read("Club.".Configure::read('Club.id').'.'.$privilege) === true) {
        if($suffix == null) {
            $suffix = '';
        }
        echo $this->Html->link($name, $url) . $suffix;
    }
}
?>