<!DOCTYPE html>
<html lang=en>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# event: http://ogp.me/ns/event#">
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $title_for_layout; ?>
        </title>
        <?php echo $this->element('layout_dependencies') ?>
        <?php echo $this->Html->css('other.css');?>
        <?php echo $scripts_for_layout ?>
    </head>
    <body>
        <?php
        if(!empty($this->Menu)) { ?>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button> 
                    <a class='navbar-brand' href='/'><?php echo Configure::read('Club.name') ?></a>
                </div>
                <div class="collapse navbar-collapse" id="main-navbar-collapse">
                    <ul class="nav navbar-nav">
<?php
echo $this->Menu->item('Events', '/events/index');
echo $this->Menu->item('Maps', '/maps/');
echo $this->Menu->item('Export', '/pages/export/');
if($this->User->hasOfficialsAccess()) {
    echo $this->Menu->item('Officials', '/officials/');
}
?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="divider"></li>
<?php
if ($this->User->isSignedIn()) {
    if ($this->User->hasAdminAccess()) {
        echo $this->Menu->item('Admin', '/pages/admin/');
    }
    echo $this->Menu->item('My Profile', $this->User->profileURL());
    echo $this->Menu->item('Sign out', '/users/logout/');
} else {
    echo $this->Menu->item('Sign in/Sign up', '/users/login');
}
?>
                    </ul>
                </div>
            </div>
        </nav>
        <?php } ?>
        <div class="container" id="content">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Session->flash('auth'); ?>
            <?php echo $content_for_layout; ?>
        </div>
        <footer class="other-footer">
            <div class="container">
                &copy; <?php echo date('Y').' Orienteering Canada'?>
                <span class="pull-right">By Thomas Nipen, <a href="http://www.russellporter.com">Russell Porter</a>
                and Adrian Zissos</span>
            </div>
        </footer>
        <?php echo $this->element('layout_bottom_dependencies'); ?>
    </body>
</html>

