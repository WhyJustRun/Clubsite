<?php $this->set('title_for_layout', $map["Map"]["name"]); ?>
<header class="page-header">
    <?php if($edit) { ?>
    <div class="pull-right btn-toolbar">
        <?php if($view_ocad && !empty($map["Map"]["repository_path"])) { ?>
        <div class="btn-group">
            <a class="btn btn-info" href="/maps/download/<?= $map["Map"]["id"]?>"><i class="icon-download-alt icon-white"></i> Download</a>
        </div>
        <?php } ?>
        <div class="btn-group">
            <a href="/maps/edit/<?= $map["Map"]["id"]?>" class="btn btn-primary"><i class="icon-cog icon-white"></i> Edit</a>
        </div>
        <div class="btn-group">
            <a href="/maps/delete/<?= $map["Map"]["id"]?>" class="btn btn-danger" onclick="return confirm('Delete this map?');"><i class="icon-trash icon-white"></i></a>
        </div>
    </div>
    <?php } ?>
	<h1><?= $map["Map"]["name"]; ?> <small>Map information</small></h1>
</header>
<div class="row">
	<div class="span5">
        <h3>Statistics</h3>
        <table class="table table-striped table-bordered table-condensed">
            <?php if(!empty($map["Map"]["scale"])) { ?>
            <tr><th>Scale</th><td>1:<?= number_format($map["Map"]["scale"]) ?></td></tr>
            <?php } ?>
            <tr><th>Map standard</th><td><?= $map["MapStandard"]["name"]?></td></tr>
            <tr><th>Created</th><td><?= strftime("%b %Y",strtotime($map["Map"]["created"]))?></td></tr>
            <tr><th>Last modified</th><td><?= strftime("%b %Y",strtotime($map["Map"]["modified"]))?></td></tr>
            <tr><th>Events on map</th><td><?= count($events)?></td></tr>
        </table>
        <hr>
        <h3>Map image</h3>
        <?php
        if($this->Media->exists('Map', $map["Map"]["id"])) { ?>
            <?= $this->Media->linkedImage("Map", $map["Map"]["id"], '400x600', array(), array('width' => '100%')) ?>
        <? } 
        else {?>
            No map image available.
        <? } ?>
        <hr>
	    <?= $this->element('Maps/events_on_map', array('events' => $events)) ?>
	</div>
	<div class="span7">
	    <?php echo $this->element('Maps/info', array('map' => $map)); ?>
	</div>
</div>
