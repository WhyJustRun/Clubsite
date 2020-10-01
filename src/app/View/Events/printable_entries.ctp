<h2><?php echo $event["Event"]["name"]?> on <?php echo $event["Event"]["date"]?></h2>
<?php
$courses = $event["Course"];
if(count($courses) > 0) {
    foreach ($courses as $course) {
    $users = $course["Result"];?>
    <h3>Course: <?php echo $course["name"] . " (" . count($users) ." participants)"?></h3>
    <table>
        <thead>
        <tr>
            <th width="1px">&nbsp;</th><th width="1px">Name</th><th>Member</th><th width="1px" style="white-space:nowrap">SI number</th><th>Comment</th><th>X</th><th width="25%">Start time</th><th width="25%">Finish time</th><th width="25%">Total time</th>
        </tr>
        </thead>
        <?php
        $counter = 1;
        foreach ($users as $user) { ?>
        <tr>
            <td><?php echo $counter?></td>
            <td><nobr><?php echo $user["User"]["name"]?></nobr></td>
            <td><?php echo $user["User"]["is_member"] ? '✓': null ?></td>
            <td><?php echo $user["User"]["si_number"]?></td>
            <td style="font-size:x-small"><?php echo $user['registrant_comment'] ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <?php
            $counter = $counter + 1;
        }
        // Draw extra blank rows
        for ($i=0; $i < Configure::read('Event.numBlankEntries'); $i++) {?>
        <tr>
            <td><?php echo $counter + $i?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php } ?>
    </table>
    <?php }
}
else {?>
No courses defined

<?php }?>
