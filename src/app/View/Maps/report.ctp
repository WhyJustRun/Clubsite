<div class="page-header">
    <h1>List of maps</h1>
</div>

<div class="table-responsive">
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
        <?php
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
            if($this->Media->exists("Map", $mapId, "60x60"))
                $mapImage = $this->Media->image("Map", $mapId, "60x60");
            else
                $mapImage =  "";
        ?>
        <tr>
            <td><?php echo $counter?></td>
            <td><?php echo $this->Html->link($mapName, "/maps/view/$mapId")?></td>
            <td><?php echo $mapStandard?> </td>
            <td><?php echo $mapCreated?></td>
            <td><?php echo $mapModified?></td>
            <td><?php echo $mapScale?></td>
            <td><?php echo $mapLat?></td>
            <td><?php echo $mapLng?></td>
            <td><?php echo $mapImage?></td>
        </tr>
        <?php
            $counter++;
        }?>
    </table>
</div>
