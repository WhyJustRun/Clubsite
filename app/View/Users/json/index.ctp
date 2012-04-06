<?php
$this->layout = null;
 
$results = array();
foreach($users as $user) {
	$result = array();
	$result["name"] = $user["User"]["name"];
	// identifiableName may include other information like the user's club to make the user more distinguishable
	$result["identifiableName"] = $user["User"]["name"];
	$result["id"] = intval($user["User"]["id"]);
	array_push($results, $result);
}

echo json_encode($results);
?>