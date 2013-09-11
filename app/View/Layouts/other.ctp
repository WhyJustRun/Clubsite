<!DOCTYPE html>
<html lang=en>
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# event: http://ogp.me/ns/event#">
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $title_for_layout; ?>
        </title>
        <?= $this->element('layout_dependencies') ?>
        <?= $this->Html->css('other.css');?>
        <?= $scripts_for_layout ?>
    </head>
    <body>
        <header class="header">
        <div>
        </div>
        </header>
        <?php
        if(!empty($this->Menu)) { ?>
        <nav>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <? echo "<a class='brand' href='#'>" . Configure::read('Club.name') . "</a>";?>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <?php
                        echo '<li class="divider-vertical"></li>';
                        echo $this->Menu->item('Home', '/', '', true);
                        echo $this->Menu->item('Events', '/events/index');
                        echo $this->Menu->item('Maps', '/maps/');
                        if($this->Session->check('Auth.User.id')) {
                            echo $this->Menu->item('Officials', '/officials/');
                            echo $this->Menu->item('Reports', '/pages/reports/');
                            echo $this->Menu->item('Export', '/pages/export/');?>
                            </ul>
                            <ul class="nav pull-right">
                            <li class="divider-vertical"></li><?
                            echo $this->Menu->item('Admin', '/pages/admin/');
                            echo $this->Menu->item('My Profile', Configure::read('Rails.profileURL').$this->Session->read('Auth.User.id'));
                            echo $this->Menu->item('Logout', '/users/logout/', 'menu_login');
                        } else {?>
                            <?= $this->Menu->item('Export', '/pages/export/');?>
                            </ul>
                            <ul class="nav pull-right">
                            <li class="divider-vertical"></li>
                            <?= $this->Menu->item('Login/Register', '/users/login', 'menu_login');
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
        </nav>
        <?php } ?>
        <div id="content">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->Session->flash('auth'); ?>
            <?php echo $content_for_layout; ?>
        </div>
        <footer>
            <div class="navbar navbar-inverse brand">
                &copy; <?= date('Y').' Orienteering Canada'?>
                <div class="pull-right">
                        By Thomas Nipen, <a href="http://www.russellporter.com">Russell Porter</a>
                        and Adrian Zissos
                </div>
            </div>
        </footer>
        <?= $this->element('sql_dump') ?>
        <?= $this->element('google_analytics') ?>
    </body>
</html>

