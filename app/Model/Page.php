<?php
class Page extends AppModel {
	var $name = 'Page';
	var $displayField = 'name';
	var $actsAs = array('Containable');
}
?>
