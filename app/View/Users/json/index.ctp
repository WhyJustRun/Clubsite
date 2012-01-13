<?php
$this->layout = null;
 
$results = array();
foreach($users as $user) {
	$result = array();
	$result["label"] = $user["User"]["name"];
	$result["id"] = intval($user["User"]["id"]);
	$result["value"] = $user["User"]["name"];
	array_push($results, $result);
}

echo json_encode($results);
?>