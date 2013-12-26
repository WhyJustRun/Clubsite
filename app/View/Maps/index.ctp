<div class="page-header">
    <div class="pull-right">
        <div class="btn-toolbar">
            <div class="btn-group"><a href="/maps/report" class="btn btn-info"><span class="glyphicon glyphicon-list"></span> Maps</a></div>
            <?php
            if($edit) {
                echo '<div class="btn-group"><a href="/maps/edit" class="btn btn-success"><span class="glyphicon-plus"></span> Map</a></div>';
            }
            ?>
        </div>
    </div>
    
    <h1>Maps</h1>
</div>
<div class="row">
    <div class="col-sm-4">
        <?= $this->ContentBlock->render('general_maps_information', null, '<hr class="divider" />') ?>
    </div>
    <div class="col-sm-8">
        <?php
        $minLat = 90;
        $maxLat = -90;
        $minLng = 180;
        $maxLng = -180;
    
        $contentsMinWidth = 230;
        $delta = 0.01; // boundary padding in degrees
    
        $options = array();
        $options['markers'] = array();
    
        foreach($maps as $map) {
            $minLat = min($minLat, $map["Map"]["lat"]);
            $maxLat = max($maxLat, $map["Map"]["lat"]);
            $minLng = min($minLng, $map["Map"]["lng"]);
            $maxLng = max($maxLng, $map["Map"]["lng"]);
    
            if($this->Media->exists('Map', $map["Map"]["id"], '60x60')) {
               $image = "<br>".$this->Media->image("Map", $map["Map"]["id"], '60x60', array('onload' => 'swapHiDPIImages();'));
            } else {
               $image = null;
            }
        
            $content = '<h3>' . $map["Map"]["name"] . '</h3>' . ' (<a href="/maps/view/' . $map["Map"]["id"] . '">More info</a>)' . $image;
    
            if($map["Map"]["map_standard_id"] == 1) {
                $iconUrl = "/img/leaflet/greenmarker.png";
            }
            else {
                $iconUrl = "/img/leaflet/redmarker.png";
            }
    
            $marker = array();
            $marker['lat'] = $map["Map"]["lat"];
            $marker['lng'] = $map["Map"]["lng"];
            $marker['content'] = $content;
            $marker['content-min-width'] = $contentsMinWidth;
            $marker['icon-url'] = $iconUrl;
            $marker['icon-width'] = 12;
            $marker['icon-height'] = 20;
            $marker['shadow-width'] = 20;
            $marker['shadow-height'] = 20;
            array_push($options['markers'], $marker);
        }
    
        $options['map'] = array();
        $options['div'] = array();
        $options['div']['height'] = '700px';
        $options['map']['bounds']['north'] = $maxLat + $delta;
        $options['map']['bounds']['south'] = $minLat - $delta;
        $options['map']['bounds']['east'] = $maxLng + $delta;
        $options['map']['bounds']['west'] = $minLng - $delta;
    
        echo $this->Leaflet->map($options);
        ?>
        <img src="/img/leaflet/redmarker.png"> Sprint maps (ISSOM)
        <img src="/img/leaflet/greenmarker.png"> Other maps (ISOM)
    </div>
</div>

