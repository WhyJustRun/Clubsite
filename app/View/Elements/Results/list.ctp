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
            $entries = $entryCount == 1 ? "Entries" : "Entries ($entryCount)"; ?>
            <th width='100%'><?= $entries ?></th>;
            <th colspan='2'>Ride?</th>
        </tr>
    </thead>
    <tbody>
    <?
    $userId = $this->Session->read('Auth.User.id');
    foreach($results as $result) {
        echo "<tr>";
        echo "<td>";
        echo $this->Html->link($result["User"]["name"], Configure::read('Rails.profileURL').$result["User"]["id"]);
        if(!empty($userId) && (!empty($result['Registrant']['id']) && $result['Registrant']['id'] == $userId || $result['User']['id'] == $userId)) {
           echo "<span class='pull-right'>";
           echo '<a class="btn btn-xs btn-danger" href="/courses/unregister/'.$course['id'].'/'.$result["User"]["id"].'"><span class="glyphicon glyphicon-minus"></span> Unregister</a>';
           echo "</span>";
        }
        echo "</td>";
        $id = SessionHelper::read('Auth.User.id');
        echo "<td colspan='2'>";
        if($result["needs_ride"] == true) {
            echo "<span class='glyphicon glyphicon-user' title='Needs ride'></span>";
        } else if($result["offering_ride"] == true) {
            echo "<span class='glyphicon glyphicon-road' title='Offering ride'></span>";
        }
        echo "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
    </table>
<?php
}
