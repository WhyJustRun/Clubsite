<?php 
$this->layout = null;
foreach($event["Course"] as &$course) {
	foreach($course["Result"] as &$result) {
		$result["non_competitive"] = $result["non_competitive"] === '1' ? true : false;
		$result["needs_ride"] = $result["needs_ride"] === '1' ? true : false;
		$result["offering_ride"] = $result["offering_ride"] === '1' ? true : false;
	}
}
echo json_encode($event);
?>