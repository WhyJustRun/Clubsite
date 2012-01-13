<?php

?>
<div class="right">
   <?php 
      echo $this->Form->create('Series', array('url' => '/Series/edit/'));
      echo $this->Form->end("Add"); 
   ?>
</div>
<header>
   <h1>Series</h1>
</header>
	<div class="column span-24">
      <div class="results-list">
      <table>
         <thead>
             <tr> <th>Acronym</th><th>Name</th><th>Description</th><th></th><th></th></tr>
         </thead>
         <?php 
         foreach($series as $serie) {?>
            <tr>
               <td><?=$serie["Series"]["acronym"]?></td>
               <td><?=$serie["Series"]["name"]?></td>
               <td><?=$serie["Series"]["description"]?></td>
               <td><?= $this->Html->link('View', '/series/view/'.$serie["Series"]["id"], array('class' => 'button'));?>
               <td><?= $this->Html->link('Edit', '/series/edit/'.$serie["Series"]["id"], array('class' => 'button'));?>
               </td>
            </tr>
         <?}?>
      </table>
      </div>
   </div>
