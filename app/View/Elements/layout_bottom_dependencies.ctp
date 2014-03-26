<?php
echo $this->element('sql_dump');
echo $this->element('google_analytics');
$css = Configure::read('TextEditor.css');
if (!empty($css)) {
    echo $this->Html->css($css);
}

$mainFile = '/js/main.js';
$cacheKey = '';
if (Configure::read('Clubsite.isProduction')) {
  $cacheKey = '?build-cached=' . Configure::read('Build.hash');
  $mainFile = '/js/main-built.js' . $cacheKey;
}

echo $this->Html->script('/js/require.js' . $cacheKey, array('data-main' => $mainFile));
?>
