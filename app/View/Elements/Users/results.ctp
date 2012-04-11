<div class="results-list">
    <h2>Results/Registrations (<?= count($results); ?>)</h2>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>Date</th>
                <th>Event</th>
                <th>Time</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $statuses = Configure::read("Result.statuses");
        $statuses["ok"] = null;
        foreach($results as $result) {
            $time = $result["Result"]["time"];
            $event = $result["Course"]["Event"]["name"];
            $eventId = $result["Course"]["event_id"];
            $date = substr($result["Course"]["Event"]["date"],0,10);
            $date = date("M j, Y", strtotime($date));
            $points = $result["Result"]["points"];
            $url = "/Events/view/$eventId";
            echo "<tr>";
            echo "<td>$date</td>";
            echo "<td><a href=\"$url\">$event</a></td>";
            $timeOrStatus = !empty($result["Result"]["time"]) && $result["Result"]["status"] === 'ok' ? $result["Result"]["time"] : $statuses[$result["Result"]["status"]];
            echo "<td>$timeOrStatus</td>";
            echo "<td>$points</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
