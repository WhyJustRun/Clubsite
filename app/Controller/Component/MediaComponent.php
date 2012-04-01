<?php
class MediaComponent extends Component {
    private $defaultType;
    private $allowedExts;
    private $thumbnailExtension = 'png';
    private $controller;

    function __construct($collection, $options = array()) {
        $this->defaultType = !empty($options['type']) ? $options['type'] : null;
        $this->allowedExts = !empty($options['allowedExts']) ? $options['allowedExts'] : null;
        $this->thumbnailSizes = !empty($options['thumbnailSizes']) ? $options['thumbnailSizes'] : null;
        $this->thumbnailExtension = !empty($options['thumbnailExt']) ? $options['thumbnailExt'] : $this->thumbnailExtension;
    }
    
    function initialize($controller) {
        $this->controller = $controller;
    }

    /**
    * $file is a PHP file upload associativeArray
    */
    public function create($file, $id, $type = null) {
        if(empty($file['name']) || empty($file['tmp_name'])) {
            throw new Exception('No file to create was provided.');
        }
    
        $this->loadType($type);
        $this->delete($id, $type);
        
        $folder = $this->folder($type);
        $ext = $this->extensionOf($file['name']);
        $filename = $folder . $id . '.' . $ext;
        if(in_array($ext, $this->allowedExts)) {
            move_uploaded_file($file['tmp_name'], $filename);
        } else {
            $exts = implode(',', $this->allowedExts);
            throw new Exception('The uploaded file format is not allowed. Please use one of ' . $exts);
        }
        
        foreach($this->thumbnailSizes as $thumbnailSize) {
            $thumbnailPath = $folder . $id . '_' . $thumbnailSize . '.' . $this->thumbnailExtension;
            $this->createThumbnail($filename, $thumbnailPath, $thumbnailSize);
        }
    }
    
    public function delete($id, $type = null) {
        $this->loadType($type);
        $folder = $this->folder($type);        
        $file = $this->findFile($id, $folder);
        if($file) {
            $this->deleteFile($file);
        }
        
        $this->deleteFiles($this->findThumbnails($id, $folder));
    }
    
    // $thumbnail can be false or the desired size (100x100) for example
    public function get($id, $thumbnail = false, $type = null) {
        $this->loadType($type);
        $folder = $this->folder($type);

        if(!$thumbnail) {
            return $this->findFile($id, $folder);
        } else {
            return $this->findThumbnail($id, $folder, $thumbnail);
        }
    }
    
    public function exists($id, $thumbnail = false, $type = null) {
        $file = $this->get($id, $thumbnail, $type);
        return $file ? true : false;
    }
    
    
    // deprecated
    public function attachResources(&$entities, $resources) {
        foreach($entities as &$entity) {
            $this->attachResource($entity);
        }
        return $entities;
    }
    
    // deprecated
    public function attachResource(&$entity, $resources) {
        if($entity[$this->defaultType]['id']) {
            foreach($resources as $resource) {
                $id = $entity[$this->defaultType]['id'].'-'.$resource;
                $source = $this->get($id);
                if($source) {
                    $entity[$options['type']]['resources'][$resource]['source'] = $source;
                    foreach($this->thumbnailSizes as $thumbnail) {
                        $entity[$options['type']]['resources'][$resource][$size] = $this->get($id, $thumbnail);
                    }
                }
            }
        }
        return $entity;
    }
    
    public function display($id, $thumbnail = false, $type = null) {
        $this->loadType($type);
        $file = $this->get($id, $thumbnail, $type);
        $path = '/';
        if(!$file) {
            $file = "$type.".$this->thumbnailExtension;
            $path = 'webroot/img/defaults/';
        }

        $this->controller->viewClass = 'Media';
        $params = array(
            'id' => $file,
            'name' => $file,
            'download' => false,
            'extension' => $this->extensionOf($file),
            'path' => $path
        );
        $this->controller->set($params);
    }
    
    private function createThumbnail($source, $destination, $size) {
        shell_exec("convert '$source' -resize $size\> '$destination'");
    }

    public function createCroppedThumbnail($id, $type, $size) {
        $this->loadType($type);
        $folder = $this->folder($type);
        $source = $this->findFile($id, $folder);

        $image = new Imagick($source);
        $d = $image->getImageGeometry();
        $origSizeX = $d['width'];
        $origSizeY = $d['height']; 
        //echo "ORIGINAL SIZE $origSizeX $origSizeY";

        $destination = $folder . $id . "_$size." . $this->thumbnailExtension;
        $offsetX = rand(0,$origSizeX);
        $offsetY = rand(0,$origSizeY);
        //echo "OFFSETS $offsetX, $offsetY";
        $str = "convert -crop ${size}+${offsetX}+${offsetY} -resize " . $size .' +repage ' . $source . ' ' . $destination;
        //echo "STRING $str";
        shell_exec("convert -crop ${size}+${offsetX}+${offsetY} -resize $size +repage $source $destination");
    }
    
    private function findFile($id, $folder) {
        $matches = glob($folder . $id . ".*");
        if(count($matches) > 1) {
            throw new Exception('Found more than one matching file');
        } else if(count($matches) == 0) {
            return false;
        }
        
        return $matches[0];
    }

    private function findThumbnails($id, $folder) {
        return glob($folder . $id . "_*." . $this->thumbnailExtension);
    }
    
    private function findThumbnail($id, $folder, $thumbnail) {
        $matches = glob($folder . $id . "_$thumbnail." . $this->thumbnailExtension);
        if(count($matches) > 1) {
            throw new Exception('Found more than one matching thumbnail');
        } else if(count($matches) == 0) {
            return false;
        }
        
        return $matches[0];
    }
    
    private function folder($type) {
        $folder = Configure::read("$type.dir");
        if(!$folder) {
            throw new Exception("Couldn't find the media folder for type: $type");
        }
        return $folder;
    }
    
    private function deleteFiles($files) {
        foreach($files as $file) {
            $this->deleteFile($file);
        }
    }
    
    private function deleteFile($file) {
        shell_exec("rm -f '$file'");
    }
    
    private function extensionOf($file) {
        $path = pathinfo($file);
        return strtolower($path['extension']);
    }
    
    private function loadType(&$type) {
        $type = $type ? $type : $this->defaultType;
        if(!$type) {
            throw new Exception("Failed loading media type.");
        }
    }
}
?>
