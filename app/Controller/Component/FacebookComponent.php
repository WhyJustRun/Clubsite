<?php
try {
    App::import("Vendor", "facebook");
} catch(Exception $e) {
    throw new InternalErrorException($e);
}

class FacebookComponent extends Component {
    function __construct($view) {
        $config = Configure::read('Facebook');
        $this->facebook = new Facebook(array(
          'appId'  => $config['app']['id'],
          'secret' => $config['app']['secret']
        ));
        parent::__construct($view);
    }

    function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    public function transformPageURLToID($url)
    {
        $components = parse_url($url);
        if (!empty($components['path'])) {
            try {
                // There is no reliable way to convert a Facebook page URL to a graph id.. The best hack is to take the last component in the url path.
                $page = $this->facebook->api(substr(strrchr($components['path'],'/'),1));
                return $page['id'];
            } catch(Exception $e) {
                return NULL;
            }
        }

        return NULL;
    }

    public function transformPageIDToURL($id)
    {
        if (empty($id)) return NULL;
        return 'http://www.facebook.com/'.$id;
    }
}
