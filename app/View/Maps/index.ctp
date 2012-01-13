<div class="column span-8">
    <?
    $startsWith = '<div class="column-box">';
    $endsWith = '</div>';
    echo $this->ContentBlock->render('general_maps_information', $startsWith, $endsWith);
    ?>
</div>
<div class="column span-16 last">
<?php
$minLat = 90;
$maxLat = -90;
$minLng = 180;
$maxLng = -180;

$contentsMinWidth = 150;
$delta = 0.01; // boundary padding in degrees

$options = array();
$options['markers'] = array();

foreach($maps as $map) {
	$minLat = min($minLat, $map["Map"]["lat"]);
	$maxLat = max($maxLat, $map["Map"]["lat"]);
	$minLng = min($minLng, $map["Map"]["lng"]);
	$maxLng = max($maxLng, $map["Map"]["lng"]);

	if($this->Media->exists('Map', $map["Map"]["id"])) {
	   $image = "<br>".$this->Media->image("Map", $map["Map"]["id"], '50x50');
	} else {
	   $image = null;
	}
	
	$content = $map["Map"]["name"] . ' (<a href="/maps/view/' . $map["Map"]["id"] . '">More info</a>)' . $image;

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
    <br/><br/>
    <?php 
    if($edit) {
        echo $this->Html->link('Add', '/Maps/edit', array('class' => 'button'));
    }
    ?>
</div>
