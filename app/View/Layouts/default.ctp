<!DOCTYPE html>
<html lang=en>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# event: http://ogp.me/ns/event#">
        <?php echo $this->Html->charset(); ?>
        <title><?php echo $title_for_layout ?></title>

        <?php echo $this->element('layout_dependencies') ?>
        <?php echo $scripts_for_layout ?>
    </head>
    <body>
        <header class="navbar-constrained-width">
            <div class="hidden-xs">
                <?php if(!empty($clubResources['headerImage'])) { ?>
                <a href="/">
                    <img width="100%" src="<?php echo $clubResources['headerImage_1300']; ?>" srcset="<?php echo $clubResources['headerImage_1300']; ?> 1x, <?php echo $clubResources['headerImage_2600']; ?> 2x" />
                </a>
                <?php } else { ?>
                <h1><?php echo Configure::read('Club.name') ?></h1>
                <?php } ?>
            </div>
            <?php
            // When the page doesn't exist, CakePHP can't find the controller and doesn't load the Menu helper.
            if(!empty($this->Menu)) { ?>
            <nav class="navbar navbar-colored navbar-squared-top navbar-constrained-width" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand visible-xs"><?php echo Configure::read('Club.acronym'); ?></a>
                </div>

                <div class="collapse navbar-collapse" id="main-navbar-collapse">
                    <ul class="nav navbar-nav navbar-nav-main-text">
                        <?php
                        echo $this->Menu->item('Home', '/', '', true);
                        echo $this->Menu->item('Events', '/events/listing', 'visible-xs');
                        echo $this->Menu->item('Calendar', '/events/index', 'hidden-xs');
                        echo $this->Menu->item('Maps', '/maps/');
                        echo $this->Menu->item('Resources', '/pages/resources');
                        echo $this->Menu->item('Contact', '/pages/contact');
                        ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-nav-main-text navbar-right">
                        <?php
                        if($this->User->isSignedIn()) {
                            if ($this->User->hasAdminAccess()) {
                                echo $this->Menu->item('Admin', '/pages/admin');
                            }
                            echo $this->Menu->item('My Profile', $this->User->profileURL());
                            echo $this->Menu->item('Sign out', '/users/logout/');
                        } else {
                            echo $this->Menu->item('Sign in/Sign up', '/users/login/');
                        }
                        ?>
                    </ul>
                </div>
            </nav>
            <?php } ?>
        </header>
        <div id="content">
            <div class="container">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Session->flash('auth'); ?>
                <?php echo $content_for_layout; ?>

                <?php echo $this->element('sql_dump'); ?>
            </div>
        </div>
        <footer class="main-footer">
            <div class="container">
                <a href="<?php echo Configure::read("Rails.domain") ?>">whyjustrun.ca</a>
                <span class="pull-right">
                    <?php echo $this->Html->link('API', 'https://github.com/WhyJustRun/Core/wiki/API') ?> |
                    <?php echo $this->Html->link('Get this website for your club', 'https://github.com/WhyJustRun/Core/wiki/Get-WhyJustRun-for-your-club!') ?>
                </span>
            </div>
            <div class="container">
                <span id="copyright">
                    &copy; <?php echo date('Y').' '.Configure::read('Club.name') ?>
                </span>
                <span id="credits" class="pull-right">
                    By <a href="">Thomas Nipen</a> and <a href="http://www.russellporter.com">Russell Porter</a> | <a href="mailto:support@whyjustrun.ca">support@whyjustrun.ca</a>
                </span>
            </div>
        </footer>
        <?php echo $this->element('layout_bottom_dependencies'); ?>
    </body>
</html>
