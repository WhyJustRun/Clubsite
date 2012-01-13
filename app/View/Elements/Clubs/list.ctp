<?php $clubs = $this->requestAction('clubs/index/'); ?>
<?php 
// Determine distance to all other clubs
$lat0 = deg2rad(configure::read('Club.lat'));
$lng0 = deg2rad(configure::read('Club.lng'));
foreach($clubs as &$club) {
    $lat1 = deg2rad($club['Club']['lat']);
    $lng1 = deg2rad($club['Club']['lng']);
    $distance = acos(
        cos($lat0) * cos($lng0) * cos($lat1) * cos($lng1)
        + cos($lat0) * sin($lng0) * cos($lat1) * sin($lng1)
        + sin($lat0) * sin($lat1)
    ) * 6.37E3;
    $club['Club']['distance'] = $distance;
}
// Sort by distance
$clubs = @Set::sort($clubs, "{n}.Club.distance", 'asc');

// Write out links to nearby club
$separator = null;
$counter = 0;
foreach($clubs as $club) {
    if($club['Club']['visible'] && $counter < Configure::read('Club.numNearbyClubs')) {
        $clubURL = $club["Club"]["url"];
        echo $separator.$this->Html->link($club["Club"]["location"], $clubURL)." ";
        $separator = "| ";
        $counter++;
    }
}; ?>
