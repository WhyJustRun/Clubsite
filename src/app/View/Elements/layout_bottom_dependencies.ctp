<?php
echo $this->element('sql_dump');
echo $this->element('google_analytics');

$mainFile = Configure::read('Clubsite.isProduction') ? '/js/main-minified.js' : '/js/main.js';
echo $this->Html->script('/js/require.js', array('data-main' => $mainFile));
?>
