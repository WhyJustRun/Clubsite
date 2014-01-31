<?php
echo $this->element('sql_dump');
echo $this->element('google_analytics');
echo $this->fetch('primaryScripts');
echo $this->fetch('secondaryScripts');
echo $this->Html->css("/ccss/fullcalendar.css,bootstrap-datetimepicker.min.css,jquery.fancybox.css,leaflet.css,ladda-themeless.min.css");
?>
