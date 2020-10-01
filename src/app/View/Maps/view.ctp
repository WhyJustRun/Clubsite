<?php $this->set('title_for_layout', $map["Map"]["name"]); ?>
<header class="page-header">
<?php
$showFileDownload = !empty($map['Map']['file_url']);
if($edit || $showFileDownload) { ?>
    <div class="pull-right btn-toolbar">
        <?php if($showFileDownload) { ?>
        <div class="btn-group">
            <a class="btn btn-info" href="/maps/download/<?php echo $map["Map"]["id"]?>"><span class="glyphicon glyphicon-download-alt"></span> Download</a>
        </div>
        <?php } ?>
        <?php if($edit) {?>
        <div class="btn-group">
            <a href="/maps/edit/<?php echo $map["Map"]["id"]?>" class="btn btn-primary"><span class="glyphicon glyphicon-cog"></span> Edit</a>
        </div>
        <div class="btn-group">
            <a href="/maps/delete/<?php echo $map["Map"]["id"]?>" class="btn btn-danger" onclick="return confirm('Delete this map?');"><span class="glyphicon glyphicon-trash"></span></a>
        </div>
        <?php }?>
    </div>
    <?php } ?>
    <h1><?php echo $map["Map"]["name"]; ?> <small>Map information</small></h1>
</header>
<div class="row">
    <div class="col-sm-5">
        <h3>Statistics</h3>
        <table class="table table-striped table-bordered table-condensed">
            <?php if(!empty($map["Map"]["scale"])) { ?>
            <tr><th>Scale</th><td>1:<?php echo number_format($map["Map"]["scale"]) ?></td></tr>
            <?php } ?>
            <tr><th>Map standard</th><td><?php echo $map["MapStandard"]["name"]?></td></tr>
            <tr><th>Events on map</th><td><?php echo count($events)?></td></tr>
        </table>
        <hr/>
        <?php if (!empty($map['Map']['notes'])) { ?>
        <h3>Notes</h3>
        <?php echo $map['Map']['notes'] ?>
        <hr/>
        <?php } ?>
        <h3>Map image</h3>
        <?php
        if($this->Media->exists('Map', $map["Map"]["id"])) { ?>
            <?php echo $this->Media->linkedImage("Map", $map["Map"]["id"], '400x600', array(), array('class' => 'fitting-image')) ?>
        <?php } else {?>
            No map image available.
        <?php } ?>
        <hr>
        <?php echo $this->element('Maps/events_on_map', array('events' => $events)) ?>
    </div>
    <div class="col-sm-7">
        <?php echo $this->element('Maps/info', array('map' => $map)); ?>
    </div>
</div>

