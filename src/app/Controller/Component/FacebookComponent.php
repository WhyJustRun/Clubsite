<?php
class FacebookComponent extends Component {
    function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    public function transformPageURLToID($url)
    {
        $components = parse_url($url);
        if (!empty($components['path'])) {
            $path = rtrim($components['path'], '/');
            return substr(strrchr($path, '/'), 1);
        }
        
        return NULL;
    }
    
    public function transformPageIDToURL($id)
    {
        if (empty($id)) return NULL;
        return 'https://www.facebook.com/'.$id;
    }
}
