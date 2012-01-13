<!DOCTYPE html>
<html lang=en>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>

    <?php
    echo $this->Html->meta('icon');
    
    echo $this->Html->css("blueprint/screen", null, array("media"=> "screen, projection"));
    echo $this->Html->css("blueprint/print", null, array("media"=> "print"));
    echo $this->Html->css('/ccss/fullcalendar.css,jquery.ketchup.css,jquery.fancybox-1.3.4.css,jquery-ui-1.8.16.custom.css,leaflet.css,gvoc.css');
    echo "<!--[if lt IE 8]>";
    echo $this->Html->css('blueprint/ie');
    echo "<![endif]-->";
    
    echo "<!--[if lte IE 8]>";
    echo $this->Html->css('leaflet.ie');
    echo "<![endif]-->";

    echo $this->Html->css("embed");
    
    echo $this->element('Series/css', array(), array('cache' => array('config' => 'view_short')));
    
    echo "<!--[if lt IE 9]>";
    // Fix CSS selectors using html5 elements
    echo $this->Html->script('html5.ie');
    echo "<![endif]-->";
    echo $this->Html->script("/cjs/jquery-1.6.2.min.js,jquery.fancybox-1.3.4.pack.js,placeholder.js,jquery-ui-1.8.16.custom.min.js,jquery-ui-timepicker-addon.js,underscore-min.js,jquery.tmpl.js,knockout.js,jquery.ketchup.all.min.js,jquery.jeditable.mini.js,wjr.js");
    
    // Easy editable content blocks
    if($this->Session->read('Privilege.ContentBlock.edit')) {
        echo $this->Html->script("/cjs/jquery.jeditable.mini.js,wjr-logged-in.js");
    }
    echo $scripts_for_layout;
    ?>
</head>
<body>
			<?php echo $content_for_layout; ?>
</body>
</html>
