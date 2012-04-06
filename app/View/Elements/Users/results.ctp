<div class="results-list">
	<h2>Results</h2>
   <table class="table table-striped table-bordered table-condensed">
      <thead>
         <tr>
            <th>Date</th>
            <th>Event</th>
            <th>Time</th>
            <th>Points</th>
         </tr>
      </thead>
   <? 
   foreach($results as $result) {
      $time = $result["Result"]["time"];
      if(array_key_exists("Event", $result["Course"])){
         if(array_key_exists("name", $result["Course"]["Event"])) {
            $event = $result["Course"]["Event"]["name"];
            $event_id = $result["Course"]["event_id"];
            $date = substr($result["Course"]["Event"]["date"],0,10);
            $date = date("M j, Y", strtotime($date));
            $points = $result["Result"]["points"];
            $url = "/Events/view/$event_id";
            if($result["Result"]["status"] == 'ok' && $time != NULL) {
               echo "<tr>";
               echo "<td>$date</td>";
               echo "<td><a href=\"$url\">$event</a></td>";
               echo "<td>$time</td>";
               echo "<td>$points</td>";
               echo "</tr>";
            }
         }
      }
   }
   echo "</table>";
   ?>
	
</div>
