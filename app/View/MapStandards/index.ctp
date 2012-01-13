<?php

?>
<div class="right">
   <?php 
      echo $this->Form->create('MapStandards', array('url' => '/mapStandards/edit/'));
      echo $this->Form->end("Add"); 
   ?>
</div>
<header>
   <h1>Map Standards</h1>
</header>
	<div class="column span-24">
      <div class="results-list">
      <table>
         <thead>
             <tr> <th>Name</th><th>Description</th><th></th><th></th></tr>
         </thead>
         <?php 
         foreach($mapStandards as $mapStandard) {?>
            <tr>
               <td><?=$mapStandard["MapStandard"]["name"]?></td>
               <td><?=$mapStandard["MapStandard"]["description"]?></td>
               <td><?= $this->Html->link('Edit', '/mapStandards/edit/'.$mapStandard["MapStandard"]["id"], array('class' => 'button'));?></td>
               <td>
               <?
                  echo $this->Form->create('MapStandard', array('action' => 'delete'));
                  echo $this->Form->hidden('id', array('value'=> $mapStandard["MapStandard"]["id"]));
                  echo $this->Form->submit('Remove', array('div'=>array('class'=>'unsubmit')));
               ?>
               </td>
            </tr>
         <?}?>
      </table>
      </div>
   </div>
