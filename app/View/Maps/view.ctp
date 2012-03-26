<?php
    $mapName = $map["Map"]["name"];
?>
<div class="right">
<?php 
if($edit) {
    echo $this->Html->link('Edit', '/maps/edit/'.$map["Map"]["id"], array('class' => 'button'));
    echo "<br/>";
    echo $this->Html->link('Delete','/maps/delete/'.$map["Map"]["id"], array('class' => 'button red', 'onclick' => 'return confirm("Delete this map?");'));
}
?>
</p>
</div>
<header>
	<h1 class="event-header"><?= $mapName; ?></h1>
    <h2 class="event-header">Map information</h2>
</header>
<div class="column span-12">
    <div class="column-box results-list">
        <h3>Statistics</h3>
        <table>
            <? if($map["Map"]["scale"] != ""){ ?>
            <tr><td>Scale</td><td>1:<?= number_format($map["Map"]["scale"]) ?></td></tr>
            <? } ?>
            <tr><td>Map standard</td><td><?= $map["MapStandard"]["name"]?></td></tr>
            <tr><td>Created</td><td><?= strftime("%b %Y",strtotime($map["Map"]["created"]))?></td></tr>
            <tr><td>Last modified</td><td><?= strftime("%b %Y",strtotime($map["Map"]["modified"]))?></td></tr>
            <tr><td>Events on this map</td><td><?= count($events)?></td></tr>
            <? if($view_ocad) {
                if($map["Map"]["repository_path"] != "") {?>
            <tr><td>OCAD file</td><td><a href="/maps/download/<?= $map["Map"]["id"]?>"><?= $map["Map"]["repository_path"]?></a></td></tr>
            <?    }
            }?>
        </table>
    </div>
    <div class="column-box results-list">
        <h3>Map image</h3>
        <?php
        if($this->Media->exists('Map', $map["Map"]["id"])) { ?>
            <?= $this->Media->linkedImage("Map", $map["Map"]["id"], '400x600') ?>
        <? } 
        else {?>
            No map image available.
        <?}?>
    </div>
<? /*
      <div class="column-box results-list">
         <h3>Revision history</h3>
         <table>
            <thead>
            <tr>
               <td>Date</td>
               <td>Username</td>
               <td>File size</td>
               <td>OCAD file</td>
            </tr>
            </thead>
            <?
            $revisions[0]["date"] = "2011-08-14";
            $revisions[0]["name"] = "tnipen";
            $revisions[0]["size"] = 1200;
            foreach($revisions as $revision) {?>
            <tr>
               <td><?=$revision["date"]?></td>
               <td><?=$revision["name"]?></td>
               <td><?=$revision["size"]?> KB</td>
               <td><a href="">file</a></td>
            </tr>
            <?}?>
         </table>
      </div>
 */?>
      <?php /*
      <div class="column-box">
         <h3>Events using this map</h3>
            <table>
            <?php
            foreach($events as $event) {
               $event_id = $event["Event"]["id"];
               $date = substr($event["Event"]["date"],0,10);
               $name = $event["Event"]["name"];
               $url = "/Events/view/$event_id";
               ?>
               <tr>
                  <td><?=$date ?></td>
                  <td><a href="<?=$url?>"><?=$name ?></a></td>
               </tr>
            <?}?>
            </table>
      </div>
      */?>
</div>
<div class="column span-12 last">
    <?php echo $this->element('Maps/info', array('map' => $map)); ?>
</div>
