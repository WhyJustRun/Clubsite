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
    echo $this->Html->css('/ccss/fullcalendar.css,jquery.ketchup.css,jquery.fancybox-1.3.4.css,jquery-ui-1.8.16.custom.css,leaflet.css,whyjustrun.css');
    echo "<!--[if lt IE 8]>";
    echo $this->Html->css('blueprint/ie');
    echo "<![endif]-->";
    
    echo "<!--[if lte IE 8]>";
    echo $this->Html->css('leaflet.ie');
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
    echo $this->Html->script("/cjs/jquery-1.6.2.min.js,jquery.fancybox-1.3.4.pack.js,placeholder.js,jquery-ui-1.8.16.custom.min.js,jquery-ui-timepicker-addon.js,underscore-min.js,jquery.tmpl.js,knockout.js,jquery.ketchup.all.min.js,jquery.jeditable.mini.js,jquery.timeago.js,wjr.js");
    
    // Easy editable content blocks
    if($this->Session->read('Club.'.Configure::read('Club.id').'.Privilege.ContentBlock.edit')) {
        echo $this->Html->script("/cjs/jquery.jeditable.mini.js,wjr-logged-in.js");
    }
    echo $scripts_for_layout;
    ?>
    
    <!-- UserVoice feedback -->
    <script type="text/javascript">
      var uvOptions = {};
      (function() {
        var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
        uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/OOr0QWysv98u5kUZjsi3mA.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
      })();
    </script>
</head>
<body>
	<div class="container">
		<header class="span-24 header">
            <div>
                <?php if(!empty($clubResources['headerImage'])) { ?>
                <a href="/">
                    <img style="display: block" src="<?= $clubResources['headerImage']; ?>" />
                </a>
                <? } else { ?>
                <h1><?= Configure::read('Club.name') ?></h1>
                <? } ?>
            </div>
			<nav>
				<ul>
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
		</header>
		<div class="span-24" id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->Session->flash('auth'); ?>
			<?php echo $content_for_layout; ?>
		</div>
	  	<footer class="span-24">
	  		<div>
					<span id="footer_menu">
						<?php echo $this->element('Clubs/footer', array(), array('cache' => array('config' => 'view_short'))); ?>
					</span>
					<span class="right padded">
					<?= $this->element('privileged_link', array('name' => 'Membership', 'url' => '/memberships/', 'privilege' => 'Privilege.Membership.edit', 'suffix' => ' |')) ?>
					<?= $this->element('privileged_link', array('name' => 'Event Planner', 'url' => '/events/planner', 'privilege' => 'Privilege.Event.planning', 'suffix' => ' |')) ?>
					<?= $this->element('privileged_link', array('name' => 'Admin', 'url' => '/pages/admin', 'privilege' => 'Privilege.Admin.page', 'suffix' => ' |')) ?> <?= $this->Html->link('Developer API', '/pages/api') ?>
					</span>
			</div>
			
			<div>
				<span id="copyright">
					&copy; <?= date('Y').' '.Configure::read('Club.name') ?>
				</span>
				<span id="credits" class="right padded">
					By <a href="">Thomas Nipen</a> and <a href="http://www.russellporter.com">Russell Porter</a>
				</span>
			</div>
	  	</footer>
	</div>
	<?= $this->element('sql_dump') ?>
	<?= $this->element('google_analytics') ?>
</body>
</html>
