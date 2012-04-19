<!DOCTYPE html>
<html lang=en>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>

    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css('/ccss/bootstrap.min.css,bootstrap-responsive.min.css,fullcalendar.css,datepicker.css,jquery.fancybox.css,leaflet.css,whyjustrun.css');
    
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
    echo $this->Html->script("/cjs/jquery-1.7.2.min.js,jquery.ketchup.all.min.js,ketchup-bootstrap.js,jquery.fancybox.pack.js,jquery.placeholder.min.js,underscore-min.js,knockout.js,jquery.jeditable.mini.js,jquery.timeago.js,jquery.iecors.js");
    echo $this->Html->script('/cjs/bootstrap.min.js,bootstrap-typeahead.js,bootstrap-datepicker.js');
    echo $this->Html->script('wjr');
    
    // Easy editable content blocks
    if($this->Session->read('Club.'.Configure::read('Club.id').'.Privilege.ContentBlock.edit')) {
        echo $this->Html->script("/cjs/jquery.jeditable.mini.js,wjr-logged-in.js");
    }
    echo $scripts_for_layout;
    
    echo $this->Html->script('cakebootstrap');
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- UserVoice feedback -->
<!--
    <script type="text/javascript">
      var uvOptions = {};
      (function() {
        var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
        uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/OOr0QWysv98u5kUZjsi3mA.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
      })();
    </script>
-->
</head>
<body>
	<header class="header">
        <div>
            <?php if(!empty($clubResources['headerImage'])) { ?>
            <a href="/">
                <img class="club-masthead" src="<?= $clubResources['headerImage']; ?>" />
            </a>
            <? } else { ?>
            <h1><?= Configure::read('Club.name') ?></h1>
            <? } ?>
        </div>
	</header>
	<div id="content">
	   <?php
	   // When the page doesn't exist, CakePHP can't find the controller and doesn't load the Menu helper.
	   if(!empty($this->Menu)) { ?>
		<nav>
			<ul class="container">
				<li><?php echo $this->Menu->item('Home', '/', true); ?></li>
				<li><?php echo $this->Menu->item('Calendar', '/events/index'); ?></li>
				<li><?php echo $this->Menu->item('Maps', '/maps/'); ?></li>
				<li><?php echo $this->Menu->item('Resources', '/pages/resources'); ?></li>
				<li><?php echo $this->Menu->item('Contact', '/pages/contact'); ?></li>
				<?php if($this->Session->check('Auth.User.id')) { ?>
				<li class="menu_login"><?php echo $this->Menu->item('My Profile', '/users/view/'.$this->Session->read('Auth.User.id')); ?></li>
				<li class="menu_login"><?php echo $this->Menu->item('Logout', '/users/logout/'); ?></li>
				<?php } else { ?>
				<li class="menu_login"><?php echo $this->Menu->item('Login/Register', '/users/login/'); ?></li>
				<?php } ?>
			</ul>
		</nav>
		<?php } ?>
		<div class="container">
    		<?php echo $this->Session->flash(); ?>
    		<?php echo $this->Session->flash('auth'); ?>
    		<?php echo $content_for_layout; ?>
		</div>
	</div>
  	<footer>
  		<div>
        	<span id="footer_menu">
        		<?php echo $this->element('Clubs/footer', array(), array('cache' => array('config' => 'view_short'))); ?>
        	</span>
        	<span class="pull-right">
        	<?= $this->element('privileged_link', array('name' => 'Event Planner', 'url' => '/events/planner', 'privilege' => 'Privilege.Event.planning', 'suffix' => ' |')) ?>
        	<?= $this->element('privileged_link', array('name' => 'Admin', 'url' => '/pages/admin', 'privilege' => 'Privilege.Admin.page', 'suffix' => ' |')) ?> 
        	
        	<?= $this->Html->link('API', 'https://github.com/OrienteerApp/OrienteerApp/wiki/API') ?> | 
        	<?= $this->Html->link('Get this website for your club', 'https://github.com/OrienteerApp/OrienteerApp/wiki/Get-WhyJustRun!') ?>
        	</span>
		</div>
		<div>
			<span id="copyright">
				&copy; <?= date('Y').' '.Configure::read('Club.name') ?>
			</span>
			<span id="credits" class="pull-right">
				By <a href="">Thomas Nipen</a> and <a href="http://www.russellporter.com">Russell Porter</a>
			</span>
		</div>
  	</footer>
	<?= $this->element('sql_dump') ?>
	<?= $this->element('google_analytics') ?>
</body>
</html>
