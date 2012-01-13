<?php

?>
<div class="right">
   <?php 
      echo $this->Form->create('Roles', array('url' => '/roles/edit/'));
      echo $this->Form->end("Add"); 
   ?>
</div>
<header>
   <h1>Roles</h1>
</header>
	<div class="column span-24">
      <div class="results-list">
      <table>
         <thead>
             <tr> <th>Name</th><th>Description</th><th></th><th></th></tr>
         </thead>
         <?php 
         foreach($roles as $role) {?>
            <tr>
               <td><?=$role["Role"]["name"]?></td>
               <td><?=$role["Role"]["description"]?></td>
               <td><?= $this->Html->link('Edit', '/roles/edit/'.$role["Role"]["id"], array('class' => 'button'));?></td>
               <td>
               <?
                  echo $this->Form->create('Role', array('action' => 'delete'));
                  echo $this->Form->hidden('id', array('value'=> $role["Role"]["id"]));
                  echo $this->Form->submit('Remove', array('div'=>array('class'=>'unsubmit')));
               ?>
               </td>
            </tr>
         <?}?>
      </table>
      </div>
   </div>
