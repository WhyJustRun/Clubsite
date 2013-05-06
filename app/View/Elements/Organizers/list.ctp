<?php
$separator = "";
foreach($organizers as $organizer) {
    echo $separator."<b>".$this->Html->link($organizer["User"]["name"], Configure::read('Rails.profileURL').$organizer["User"]["id"])."</b>";
    if(!empty($organizer["Role"]))
        echo " (" . $organizer["Role"]["name"]. ")";
    $separator = ", ";
}
?>

