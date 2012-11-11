<?php
echo $this->Html->meta('icon');
echo $this->Html->css('/ccss/bootstrap.min.css,font-awesome.css,fullcalendar.css,datepicker.css,jquery.fancybox.css,leaflet.css,whyjustrun.css');

echo "<!--[if lte IE 8]>";
echo $this->Html->css('leaflet.ie');
echo "<![endif]-->";

echo "<!--[if lte IE 7]>";
echo $this->Html->script('ie7/warning');
echo '<script>window.onload=function(){e("/js/ie7/")}</script>';
echo "<![endif]-->";

echo $this->element('Series/css', array(), array('cache' => array('config' => 'view_short')));

// Custom club CSS uploaded through the admin interface
if(!empty($clubResources['style'])) {
    echo $this->Html->css($clubResources['style']);
}

echo "<!--[if lt IE 9]>";
// Fix CSS selectors using html5 elements
echo $this->Html->script('html5.ie');
echo "<![endif]-->";
echo $this->Html->script("/cjs/jquery-1.8.2.min.js,jquery.mousewheel-3.0.6.pack.js,jquery.ketchup.all.min.js,ketchup-bootstrap.js,jquery.fancybox.pack.js,jquery.placeholder.min.js,underscore-min.js,knockout.js,jquery.jeditable.mini.js,jquery.timeago.js,jquery.iecors.js");
echo $this->Html->script('/cjs/bootstrap.min.js,bootstrap-typeahead.js,bootstrap-datepicker.js');
echo $this->Html->script('wjr');

// Easy editable content blocks
if($this->Session->read('Club.'.Configure::read('Club.id').'.Privilege.ContentBlock.edit')) {
    echo $this->Html->script("/cjs/jquery.jeditable.mini.js,wjr-logged-in.js");
}

echo $this->Html->script('cakebootstrap');
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
