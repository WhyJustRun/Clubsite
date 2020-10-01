<?php
class JuicerFeedComponent extends Component {
    function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    public function transformURLToID($url)
    {
        $components = parse_url($url);
        if (!empty($components['path'])) {
            $path = rtrim($components['path'], '/');
            return substr(strrchr($path, '/'), 1);
        }
        
        return NULL;
    }
    
    public function transformIDToURL($id)
    {
        if (empty($id)) return NULL;
        return 'https://www.juicer.io/feeds/'.$id;
    }
}
