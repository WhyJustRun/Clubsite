<h2>Privileges</h2>
<div class="column-box span-8">
<h3>Types of privileges</h3>
<?php foreach($groupList as $group) { ?>
   <p>
      <b><?= $group["Group"]["name"] ?></b>: <?= $group["Group"]["description"] ?>
   </p>
<?php } ?>
</div>
<div class="column-box span-14">
<h3>Add privilege</h3>
<table>
   <?=$this->Form->create('Privilege', array('action' => 'add'))?>
   <tr>
      <td> <?= $this->Form->input('user_id', array('label'=>false)) ?> </td>
      <td> <?= $this->Form->input('group_id', array('label'=>false)) ?> </td>
      <td> <?= $this->Form->end('Add') ?> </td>
   </tr>
</table>

<h3>Current privileges</h3>
<table>
   <thead>
      <tr>
         <th>Name (id)</th>
         <th>Group</th>
         <th></th>
         <th></th>
      </tr>
   </thead>
   <tbody>
   <?php
   foreach ($privileges as $privilege) {
      ?>
      <tr>
         <td><?= $privilege["User"]["name"]. " (" . $privilege["User"]["id"].")" ?></td>
         <?= $this->Form->create('Privilege', array('action' => 'edit'));?>
         <td>
         <?
            echo $this->Form->hidden('user_id', array('value'=> $privilege["User"]["id"]));
            echo $this->Form->hidden('id', array('value'=> $privilege["Privilege"]["id"]));
            echo $this->Form->input('group_id', array('value'=>$privilege["Group"]["id"], 'label'=>false));
         ?>
         </td>
         <td>
            <?= $this->Form->end('Alter') ?>
         </td>
         <td>
            <?
               echo $this->Form->create('Privilege', array('action' => 'delete'));
               echo $this->Form->hidden('id', array('value'=> $privilege["Privilege"]["id"]));
               echo $this->Form->end('Remove', array('div'=>array('class'=>'unsubmit')));
            ?>
         </td>
      </tr>
   <?}?>
   </body>
</table>
</div>
