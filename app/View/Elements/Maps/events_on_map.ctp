<?php if(!empty($events)) { ?>
    <h3>Events on this map</h3>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <th>Series</th>
            <th>Date</th>
        </thead>
        <?php
        foreach ($events as $event) {
            $date = substr($event["Event"]["date"],0,10);
            $date = date("M j, Y", strtotime($date));
            ?>
            <tr>
                <td><?php echo empty($event["Series"]["acronym"]) ? $event["Event"]["name"] : $event["Event"]["name"]." (".$event["Series"]["acronym"].")"?></td>
                <td><?php echo $this->Html->link($date, $this->Link->eventURL($event['Event'], $event['Club']))?></td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

