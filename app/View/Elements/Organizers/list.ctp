<?php
$separator = "";
foreach($organizers as $organizer) {
	echo $separator."<b>".$this->Html->link($organizer["User"]["name"], '/users/view/'.$organizer["User"]["id"])."</b>";
    echo " (" . $organizer["Role"]["name"]. ")";
	$separator = ", ";
}
?>
