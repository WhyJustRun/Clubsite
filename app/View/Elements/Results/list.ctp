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
            <th width='100%'><?= $entries ?></th>
        </tr>
    </thead>
    <tbody>
    <?
    $userId = $this->Session->read('Auth.User.id');
    foreach($results as $result) {
        echo "<tr>";
        echo "<td>";
        echo $this->Html->link($result["User"]["name"], Configure::read('Rails.profileURL').$result["User"]["id"]);
        echo "<span class='pull-right'>";
            echo "<div class='btn-toolbar'>";
            if(!empty($userId) && (!empty($result['Registrant']['id']) && $result['Registrant']['id'] == $userId || $result['User']['id'] == $userId)) {
                echo "<div class='btn-group'>";
                    $modalId = "change-comment-modal-" . $result['id'];
                    echo '<button data-toggle="modal" data-target="#' . $modalId . '" class="btn btn-xs btn-default">' . (empty($result['comment']) ? 'Add' : 'Change') . ' Comment</button>';
                    echo $this->element('Results/change_comment_modal', array('result' => $result, 'modalId' => $modalId));
                echo "</div>";
                echo "<div class='btn-group'>";
                    echo '<a class="btn btn-xs btn-danger" href="/courses/unregister/'.$course['id'].'/'.$result["User"]["id"].'"><span class="glyphicon glyphicon-minus"></span> Unregister</a>';
                echo "</div>";
            }
            if (!empty($result['comment'])) {
                echo '<div class="btn-group">';
                    echo '<button class="btn btn-xs btn-default" data-container="body" data-placement="auto" data-toggle="tooltip" title="' . htmlentities($result['comment']) . '"><span class="glyphicon glyphicon-comment"></span></button>';
                echo '</div>';
            }
            echo "</div>";
        echo "</span>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
    </table>
<?php
}
