<?php
class LinkHelper extends Helper {
    public $helpers = array('Html');

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
            return $path;
        }

        return "http://".$domain.$path;
    }

    // Builds the URL to pin some content
    public function pinterestPinUrl($contentUrl, $mediaUrl, $description) {
        $url = "//www.pinterest.com/pin/create/button/?url=";
        $url .= rawurlencode($contentUrl);
        $url .= "&media=";
        $url .= rawurlencode($mediaUrl);
        $url .= "&description=";
        $url .= rawurlencode($description);
        return $url;
    }
}
