<?php
	//print_r($rankings);
?>
<header>
	<h1>Current rankings</h1>
</header>
	<div class="column span-12">
      <div class="column-box results-list">
         <h3>Options</h3>
         <table>
            <tr><td>Scale</td><td></td></tr>
            <tr><td>Scale</td><td></td></tr>
            <tr><td>Scale</td><td></td></tr>
            <tr><td>Scale</td><td></td></tr>
         </table>
      </div>
	</div>
   <div class="column span-12 last">
      <div class="column-box results-list">
         <table>
         <thead>
            <tr> <td>Rank</td><td>Name</td><td>Points</td></tr>
         </thead>
            <? $counter = 1;
               foreach($rankings as $key=>$value) {?>
            <tr><td><?=$counter?></td><td><?=$key?></td><td><? echo sprintf("%.2f",$value);?></td></tr>
            <?$counter++;}?>
         </table>
      </div>
   </div>
