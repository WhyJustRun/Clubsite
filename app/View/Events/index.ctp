<?php
echo $this->element('Events/list_header', array('type' => 'calendar'));
echo $this->element('Events/calendar', compact($day, $month, $year));
?>
</div>
