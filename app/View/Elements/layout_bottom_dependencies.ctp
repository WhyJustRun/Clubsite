<?php
echo $this->element('sql_dump');
echo $this->element('google_analytics');
$css = Configure::read('TextEditor.css');
if (!empty($css)) {
    echo $this->Html->css($css);
}
?>
