<!DOCTYPE html>
<html lang=en>
<head>
  	<?php echo $this->Html->charset(); ?>
	  <title>
		    <?php echo $title_for_layout; ?>
	  </title>

    <?= $this->element('layout_dependencies') ?>
    <?= $scripts_for_layout ?>
</head>
<body>
	<header class="header">
        <div>
            <?php if(!empty($clubResources['headerImage'])) { ?>
            <a href="/">
                <img class="club-masthead" src="<?= $clubResources['headerImage_1300']; ?>" data-2x-src="<?= $clubResources['headerImage_2600']; ?>" />
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
				By <a href="">Thomas Nipen</a> and <a href="http://www.russellporter.com">Russell Porter</a> | <a href="mailto:support@whyjustrun.ca">support@whyjustrun.ca</a>
			</span>
		</div>
  	</footer>
	<?= $this->element('sql_dump') ?>
	<?= $this->element('google_analytics') ?>
</body>
</html>
