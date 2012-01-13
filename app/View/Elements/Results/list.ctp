<?php
// List of results for a course

// Variables passed in: type ('registrations' or 'results'), results array
?>

<?php
if(!empty($results)) { ?>
<table>
<thead>
	<tr>
		<?php 
		if($type == 'registrations') {
            $entryCount = count($results);
            $entries = $entryCount == 1 ? "Entries" : "Entries ($entryCount)";
			echo "<th width='100%'>$entries</th>";
			echo "<th colspan='2'>Ride?</th>";
		} else if($type == 'results') {
			echo "<th>Rank</th><th>Participant</th>
			<th>Time</th>
			<th>Points</th>";
		} else {
			echo "Error: Invalid result type: $type specified.<br/>";
		}
		?>
	</tr>
</thead>
<tbody>
<? }
$ncResults = 0;
$rank = 1;
foreach($results as $result) {
	
	if($type == 'registrations') {
		echo "<tr>";
		echo "<td>";
		echo $this->Html->link($result["User"]["name"], '/users/view/'.$result["User"]["id"]);
		echo "</td>";
        $id = SessionHelper::read('Auth.User.id');
        if($result["User"]["id"] == $id) {
            echo "<td>";
            echo $this->Form->create('Result', array('action' =>'editRide'));
            echo $this->Form->hidden('id', array('value' => $result["id"]));
            if($result["needs_ride"] == true) {
                echo $this->Form->hidden('needs_ride', array('value' => '0'));
                $img = 'hitch24';
            }
            else {
                echo $this->Form->hidden('needs_ride', array('value' => '1'));
                echo $this->Form->hidden('offering_ride', array('value' => '0'));
                $img = 'hitch24_bw';
            }
            echo "<div style='width: 24px'>";
            echo "<input type='image' src='/img/".$img.".gif' width='24px' title='need a ride' onsubmit='submit-form();'>";
            echo "</div></form>";
		echo "</td>";
		echo "<td>";
            echo $this->Form->create('Result', array('action' =>'editRide'));
            echo $this->Form->hidden('id', array('value' => $result["id"]));
            if($result["offering_ride"] == true) {
                echo $this->Form->hidden('offering_ride', array('value' => '0'));
                $img = 'carpool24';
            }
            else {
                echo $this->Form->hidden('offering_ride', array('value' => '1'));
                echo $this->Form->hidden('needs_ride', array('value' => '0'));
                $img = 'carpool24_bw';
            }
            echo "<div style='width: 24px'>";
            echo "<input type='image' src='/img/".$img.".gif' width='24px' title='offering a ride' onsubmit='submit-form();'>";
            echo "</div></form>";
            echo "</td>";
        }
        else {
            echo "<td colspan='2'>";
            if($result["needs_ride"] == true) {
                echo "<img src='/img/hitch24.gif' title='needs a ride'>";
            }
            else if($result["offering_ride"] == true) {
                echo "<img src='/img/carpool24.gif' title='offering a ride'>";
            }
            echo "</td>";
        }
	} else if($type == 'results') {
		if($result["non_competitive"]) {
			$ncResults++;
		}
		if($ncResults === 1) { 
			$rank = "";?>
			</tbody>
			
			<thead>
				<tr>
				<th></th>
				<th>Non-Competitive/DNF Participants</th>
				<th>Time</th>
				<th></th>
				</tr>
			</thead>
			<tbody>
		<?php }
		echo "<tr>";
		echo "<td>$rank</td><td>".$this->Html->link($result["User"]["name"], '/users/view/'.$result["User"]["id"])."</td><td>$result[time]</td><td>$result[points]</td>";
		if($rank !== "") {
			$rank++;
		}
	} else {
		echo "Error: Invalid result type: $type specified.<br/>";
	}
	echo "</tr>";
}

if(!empty($results)) { ?>
</tbody>
</table>
<?php
}
?>

