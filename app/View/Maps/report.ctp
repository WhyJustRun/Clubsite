<div class="page-header">
    <h1>List of maps</h1>
</div>

<table class="table table-striped table-bordered table-condensed">
    <thead>
        <th></th>
        <th>Name</th>
        <th>Map standard</th>
        <th>Created</th>
        <th>Modified</th>
        <th>Scale</th>
        <th>Latitude</th>
        <th>Longitide</th>
        <th>Thumbnail</th>
    </thead>
    <?
    $counter = 1;
    foreach($maps as $map) {
        $mapId   = $map["Map"]["id"];
        $mapName = $map["Map"]["name"];
        if($map["Map"]["scale"] != 0)
            $mapScale = '1:' . number_format($map["Map"]["scale"],0, '.', ',');
        else
            $mapScale = '&nbsp;';
        if($map["MapStandard"]["id"] != 0)
            $mapStandard = $map["MapStandard"]["name"];
        else
            $mapStandard = '&nbsp;';
        $mapCreated = substr($map["Map"]["created"],0,10);
        $mapModified = substr($map["Map"]["modified"],0,10);
        $mapLat = sprintf("%3.3f", $map["Map"]["lat"]);
        $mapLng = sprintf("%3.3f", $map["Map"]["lng"]);
        if($this->Media->exists("Map", $mapId, "50x50"))
            $mapImage = $this->Media->image("Map", $mapId, "50x50");
        else
            $mapImage =  "";
    ?>
    <tr>
        <td><?=$counter?></td>
        <td><?=$this->Html->link($mapName, "/maps/view/$mapId")?></td>
        <td><?=$mapStandard?> </td>
        <td><?=$mapCreated?></td>
        <td><?=$mapModified?></td>
        <td><?=$mapScale?></td>
        <td><?=$mapLat?></td>
        <td><?=$mapLng?></td>
        <td><?=$mapImage?></td>
    </tr>
    <?
        $counter++;
    }?>
</table>

