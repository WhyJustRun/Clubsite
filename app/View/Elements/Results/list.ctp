<?php
// List of registrations for a course

// Passed in: results array
$statusTable = Configure::read('Result.statuses');
$statusTable['ok'] = '';

if(!empty($results)) { ?>
    <table class="table table-striped table-bordered table-condensed">
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
        echo "<td colspan='2'>";
        if($result["needs_ride"] == true) {
            echo "<i class='icon-user' title='Needs ride'>";
        } else if($result["offering_ride"] == true) {
            echo "<i class='icon-road' title='Offering ride'>";
        }
        echo "</td>";
    	echo "</tr>";
    }
    ?>
    </tbody>
    </table>
<?php
}
?>

