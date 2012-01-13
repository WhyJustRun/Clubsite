<?php
	//print_r($organizers);
   $seriesName = $series["Series"]["name"];
?>
<div class="right">
<?php 
echo $this->Form->create('Series', array('url' => '/Series/edit/'.$series["Series"]["id"]));
echo $this->Form->end("Edit"); 
?>
</p>
</div>
<header>
	<h1><?= $seriesName; ?></h1>
	<h2><?= "Hosted by " . Configure::read("Club.acronym");?></h2>
</header>
	<div class="column span-12">
      <div class="column-box results-list">
         <h3>General information</h3>
         <?= $this->Markdown->render($series["Series"]["information"])?>
      </div>
   </div>
   <div class="column span-12 last">
      <div class="column-box results-list">
         <h3>Event list</h3>
         <table>
         <thead>
            <tr> <th>Date</th><th>Name</th></tr>
         </thead>
            <? foreach($events as $event) {?>
            <tr>
               <td><?=$event["Event"]["date"]?></td>
               <td><a href="<?='/Events/view/'.$event["Event"]["id"]?>"><?=$event["Event"]["name"]?></a></td>
            </tr>
            <?}?>

         </table>
      </div>
      <div class="column-box">
         <h3>News</h3>
         No news posted yet
      </div>
      <div class="column-box">
         <h3>Photos</h3>
         No photos posted yet
      </div>
	</div>
