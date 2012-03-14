<?php
// List of registrations for a course

// Passed in: results array
$statusTable = Configure::read('Result.statuses');
$statusTable['ok'] = '';

if(!empty($results)) { ?>
    <table>
    <thead>
    	<tr>
    		<?php 
            $entryCount = count($results);
            $entries = $entryCount == 1 ? "Entries" : "Entries ($entryCount)";
    		echo "<th width='100%'>$entries</th>";
    		echo "<th colspan='2'>Ride?</th>";
    		?>
    	</tr>
    </thead>
    <tbody>
    <?
    foreach($results as $result) {
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
            } else {
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
            } else {
                echo $this->Form->hidden('offering_ride', array('value' => '1'));
                echo $this->Form->hidden('needs_ride', array('value' => '0'));
                $img = 'carpool24_bw';
            }
            echo "<div style='width: 24px'>";
            echo "<input type='image' src='/img/".$img.".gif' width='24px' title='offering a ride' onsubmit='submit-form();'>";
            echo "</div></form>";
            echo "</td>";
        } else {
            echo "<td colspan='2'>";
            if($result["needs_ride"] == true) {
                echo "<img src='/img/hitch24.gif' title='needs a ride'>";
            } else if($result["offering_ride"] == true) {
                echo "<img src='/img/carpool24.gif' title='offering a ride'>";
            }
            echo "</td>";
        }
    	echo "</tr>";
    }
    ?>
    </tbody>
    </table>
<?php
}
?>

