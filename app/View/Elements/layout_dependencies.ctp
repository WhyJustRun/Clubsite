<?php
echo $this->Html->meta('icon');
echo $this->Html->meta(array('name' => 'wjr.core.domain', 'content' => Configure::read('Rails.domain')));
echo $this->Html->meta(array('name' => 'wjr.clubsite.club.id', 'content' => Configure::read('Club.id')));
echo $this->Html->css('/ccss/bootstrap.min.css,whyjustrun.css');

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
<?php
// echo $this->Html->script("/cjs/jquery-1.8.2.min.js,jquery.mousewheel-3.0.6.pack.js,jquery.ketchup.all.min.js,ketchup-bootstrap.js,jquery.fancybox.pack.js,jquery.placeholder.min.js,underscore-min.js,knockout.js,jquery.jeditable.mini.js,jquery.iecors.js,moment.min.js,img.srcset.polyfill.js,spin.min.js,jquery.reject.js", array('block' => 'primaryScripts'));
// echo $this->Html->script('/cjs/bootstrap.min.js,bootstrap-datetimepicker.min.js,bootstrap-typeahead.js,ladda.min.js,cakebootstrap.js', array('block' => 'primaryScripts'));
$mainFile = '/js/main.js';
if (Configure::read('Clubsite.isProduction')) {
  $mainFile = '/js/main-built.js';
}

echo $this->Html->script('require', array('data-main' => $mainFile));
echo $this->fetch('open_graph');
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
