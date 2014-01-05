<?php
echo $this->element('sql_dump');
echo $this->element('google_analytics');
echo $this->fetch('primaryScripts');
echo $this->fetch('secondaryScripts');
?>
