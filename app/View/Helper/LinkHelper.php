<?php
class LinkHelper extends Helper {
    public $helpers = array('Html');
    private $endpoints = array(
        'Course' => '/courses/map/',
        'Map' => '/maps/rendering/',
        'Result' => '/events/rendering/',
    );

    // Build the URL to an event (may not be at the same club)
    // requires Club.id, and Club.domain, as well as Event.id
    public function eventURL($event, $club) {
        if(empty($club['id']) || empty($club['domain']) || empty($event['id'])) {
            return null;
        }

        $domain = $club['domain'];
        $id = $event['id'];
        $path = "/Events/view/$id";
        if($club['id'] === Configure::read('Club.id')) {
            return 	$path;
        }

        return "http://".$domain.$path;
    }
}
?>

