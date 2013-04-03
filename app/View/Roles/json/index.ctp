<?php
$this->layout = null;
$json_roles = array();
foreach($roles as $role) {
    array_push($json_roles, array("id" => $role['Role']['id'], "name" => $role['Role']['name']));
}

echo json_encode($json_roles);
?>

