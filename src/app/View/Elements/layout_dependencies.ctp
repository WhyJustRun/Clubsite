<?php
echo $this->Html->meta('icon');
echo $this->Html->meta(array('name' => 'wjr.core.domain', 'content' => Configure::read('Rails.domain')));
echo $this->Html->meta(array('name' => 'wjr.clubsite.club.id', 'content' => Configure::read('Club.id')));

if (Configure::read('Clubsite.isProduction')) {
  echo $this->Html->css('main-minified.css');
} else {
  echo $this->Html->css('bootstrap.min');
  echo $this->Html->css('whyjustrun');
}

echo $this->element('Series/css', array(), array('cache' => array('key' => 'series_css_club_'.Configure::read('Club.id'), 'config' => 'view_short')));

// Custom club CSS uploaded through the admin interface
if(!empty($clubResources['style'])) {
    echo $this->Html->css($clubResources['style']);
}
?>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
<?php echo $this->fetch('open_graph') ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
