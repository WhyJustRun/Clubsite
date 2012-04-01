<?php if(!empty($events)) { ?>
	<div class="column-box">
		<h3>Events on this map</h3>
		<table>
			<thead>
				<th>Series</th>
				<th>Date</th>
			</thead>
			<?php
			foreach ($events as $event) {
				$date = substr($event["Event"]["date"],0,10);
				$date = date("M j, Y", strtotime($date));
				$event_id = $event["Event"]["id"];
				?>
				<tr>
					<td><?=$event["Series"]["acronym"]?></td>
					<td><?=$this->Html->link($date, "/events/view/$event_id")?></td>
				</tr>
			<? } ?>
		</table>
	</div>
<?php } ?>
