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
            $time = $result["Result"]["time_seconds"];
            $formattedTime = $this->TimePlus->formattedResultTime($time);
            $event = $result["Course"]["Event"];
            $eventName = $event['name'];
            $eventId = $event['id'];
            $date = substr($event["date"], 0, 10);
            $date = date("M j, Y", strtotime($date));
            $points = $result["Result"]["points"];
            $url = $this->Link->eventURL($event, $event['Club']);
            echo "<tr>";
            echo "<td>$date</td>";
            echo "<td><a href=\"$url\">$eventName</a></td>";
            $timeOrStatus = !empty($time) && $result["Result"]["status"] === 'ok' ? $formattedTime : $statuses[$result["Result"]["status"]];
            echo "<td>$timeOrStatus</td>";
            echo "<td>$points</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
