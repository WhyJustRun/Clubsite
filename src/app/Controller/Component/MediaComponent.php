<?php
App::uses('Component', 'Controller');

class MediaComponent extends Component {
    private $defaultType;
    private $allowedExts;
    private $controller;
    public $thumbnailSizes;

    function __construct($collection, $options = array()) {
        $this->defaultType = !empty($options['type']) ? $options['type'] : null;
        $this->allowedExts = !empty($options['allowedExts']) ? $options['allowedExts'] : null;
        $this->thumbnailSizes = !empty($options['thumbnailSizes']) ? $options['thumbnailSizes'] : null;
        $this->addHiDPISizes();
    }

    function initialize(Controller $controller) {
        $this->controller = $controller;
    }

    /**
     * $file is a PHP file upload associative array
     */
    public function create($file, $id, $type = null) {
        if(empty($file['name']) || empty($file['tmp_name'])) {
            return 'No file to create was provided.';
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
            return 'The uploaded file format is not allowed. Please use one of ' . $exts;
        }

        $this->buildImagesForFile($filename, $folder, $id);
        return null;
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
            return $this->findThumbnail($id, $folder, $thumbnail, $type);
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
            $file = "$type.png";
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

    public function buildImages($id, $type = null) {
        $this->loadType($type);
        $folder = $this->folder($type);
        $file = $this->findFile($id, $folder);

        if(!$file) return;
        $this->buildImagesForFile($file, $folder, $id);
    }

    public function createCroppedThumbnail($id, $type, $size, $position = "random") {
        $this->loadType($type);
        $folder = $this->folder($type);
        $source = $this->findFile($id, $folder);

        if($source) {
            $doubledSize = $this->doubledSize($size);
            $image = new Imagick($source);
            $d = $image->getImageGeometry();
            $origSizeX = $d['width'];
            $origSizeY = $d['height'];

            $destination = $folder . $id . "_$size." . $this->thumbnailExtensionFor($size);
            $doubledDestination = $folder . $id . "_$doubledSize." . $this->thumbnailExtensionFor($size);
            $explodedDoubledSize = explode("x", $doubledSize);
            $doubledSizeWidth = $explodedDoubledSize[0];
            $doubledSizeHeight =  $explodedDoubledSize[1];

            if ($position == "random") {
                $offsetX = rand(0,$origSizeX - $doubledSizeWidth);
                $offsetY = rand(0,$origSizeY - $doubledSizeHeight);
            } else {
                $offsetX = $origSizeX / 2;
                $offsetY = $origSizeY / 2;
            }

            $resizeCommandFirstPart = "convert -crop ${doubledSize}+${offsetX}+${offsetY} -strip -interlace Plane -gaussian-blur 0.05 -quality 85% -resize ";
            shell_exec($resizeCommandFirstPart.$size." +repage $source $destination");
            shell_exec($resizeCommandFirstPart.$doubledSize." +repage $source $doubledDestination");
        }
    }

    private function findFile($id, $folder) {
        $matches = glob($folder . $id . ".*");
        if(count($matches) > 1) {
            throw new InternalErrorException('Found more than one matching file');
        } else if(count($matches) == 0) {
            return false;
        }

        return $matches[0];
    }

    private function findThumbnails($id, $folder) {
        return glob($folder . $id . "_*.{jpg,png}");
    }

    private function thumbnailExtensionFor($thumbnail) {
        if ($thumbnail == 'image') {
            return 'jpg';
        } else {
            return 'png';
        }
    }

    // Simple protection against running multiple conversions at a time if the page gets a request and the file is uploaded but the thumbnails haven't been generated yet.
    private function imageIsBrandNew($id, $folder) {
        $file = $this->findFile($id, $folder);
        if (!$file) {
            // This shouldn't ever happen
            return true;
        } else if ((time() - filemtime($file)) < 2 * 60 * 60) {
            return true;
        }

        return false;
    }

    private function findThumbnail($id, $folder, $thumbnail, $type) {
        $matches = glob($folder . $id . "_$thumbnail." . $this->thumbnailExtensionFor($thumbnail));
        if(count($matches) > 1) {
            throw new InternalErrorException('Found more than one matching thumbnail');
        } else if(count($matches) == 0) {
            if ($this->imageRequestIsValid($id, $folder, $thumbnail) && !$this->imageIsBrandNew($id, $folder)) {
                $this->buildImages($id, $type);
            }

            $matches = glob($folder . $id . "_$thumbnail." . $this->thumbnailExtensionFor($thumbnail));
            if (count($matches) == 0) {
                return false;
            }
        }

        return $matches[0];
    }

    private function imageRequestIsValid($id, $folder, $thumbnail) {
        if ($this->findFile($id, $folder) === false) {
            return false;
        }

        if ($thumbnail != null) {
            if ($thumbnail != 'image' &&
                !in_array($thumbnail, $this->thumbnailSizes)) {
                    return false;
                }
        }

        return true;
    }

    private function buildImagesForFile($file, $folder, $id) {
        assert(!empty($file));
        $imagePath = $folder . $id . '_image.' . $this->thumbnailExtensionFor('image');
        $this->createImage($file, $imagePath, null);
        foreach($this->thumbnailSizes as $thumbnailSize) {
            $thumbnailPath = $folder . $id . '_' . $thumbnailSize . '.' . $this->thumbnailExtensionFor($thumbnailSize);
            $this->createImage($imagePath, $thumbnailPath, $thumbnailSize);
        }
    }

    // If size is null, will not do any resizing
    private function createImage($source, $destination, $size) {
        if (file_exists($destination)) {
            unlink($destination);
        }

        $command = "convert -strip -interlace Plane -gaussian-blur 0.05";
        // Handle PDFs/images with transparency (https://github.com/WhyJustRun/Clubsite/issues/176)
        $command .= " -background white -alpha remove";

        // For PDFs, create a composite image with all the pages
        if ($this->extensionOf($source) == 'pdf') {
            // Use a higher DPI for converting PDFs
            $command .= " -density 288 -colorspace RGB";
            $command .= " -quality 85% '$source'";
            $command .= " '$destination.%04d.tmp.jpg'";
            shell_exec($command);
            $command = "convert -quality 85% '$destination.*.tmp.jpg' -append '$destination'";
            shell_exec($command);
            $command = "rm -f $destination.*.tmp.jpg";
            shell_exec($command);
        } else {
            $command .= " -quality 85% '$source'";
            if ($size) {
                $command .= " -resize $size\>";
            }
            $command .= " '$destination'";
            shell_exec($command);

            if ($this->extensionOf($destination) == 'png') {
              shell_exec("optipng -o4 '$destination'");
            }
        }
    }

    private function folder($type) {
        $folder = Configure::read("$type.dir");
        if(!$folder) {
            throw new InternalErrorException("Couldn't find the media folder for type: $type");
        }

        if (!is_dir($folder)) {
            mkdir($folder);
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
            throw new InternalErrorException("Failed loading media type.");
        }
    }

    private function doubledSize($size) {
        $glue = "x";
        $components = explode($glue, $size);
        $newComponents = array();
        foreach($components as $component) {
            $newComponents[] = intval($component) * 2;
        }

        return implode($glue, $newComponents);
    }

    private function addHiDPISizes() {
        $newSizes = array();

        foreach($this->thumbnailSizes as $size) {
            $newSizes[] = $this->doubledSize($size);
        }

        $this->thumbnailSizes = array_merge($this->thumbnailSizes, $newSizes);
    }

}
