<?php
App::uses('AppModel', 'Model');

class Resource extends AppModel {
    public $displayField = 'caption';
    public $clubSpecific = false; // in general, not club specific, though *some* resources belong to a club directly (header image, logo, etc)
    public $thumbnailableFiles = array('jpg', 'jpeg', 'gif', 'png', 'pdf');
    public $modificationFields = true;

    public $belongsTo = array('Club');

    // Just for caching method calls within the same request.
    private $forClubCache = array();

    // Returns a map of club resource keys to urls, used by views
    public function forClub($clubId) {
        if (empty($this->forClubCache[$clubId])) {
            $clubResources = $this->findByClubId($clubId);
            $map = array();

            if ($clubResources != null) {
                foreach ($clubResources as $resource) {
                    $resource = $resource['Resource'];
                    $map[$resource['key']] = $this->urlForResource($resource);
                    if ($this->needsThumbnails($resource)) {
                        $sizes = $this->sizes();
                        foreach ($sizes as $size) {
                            $key = $resource['key'] . "_" . $size;
                            $map[$key] = $this->urlForResource($resource, $size);
                        }
                    }
                }
            }

            $this->forClubCache[$clubId] = $map;
        }

        return $this->forClubCache[$clubId];
    }

    public function findByClubId($clubId) {
        return $this->find('all', array(
                'conditions' => array('Club.id' => $clubId)
            )
        );
    }

    public function delete($id = null, $cascade = true) {
        if ($id === null) {
            $id = $this->id;
        }

        $resource = $this->findById($id);
        if (empty($resource)) return;
        $resource = $resource['Resource'];
        unlink($this->absolutePathForResource($resource));

        if ($this->needsThumbnails($resource)) {
            $sizes = $this->sizes();
            foreach ($sizes as $size) {
                unlink($this->absolutePathForResource($resource, $size));
            }
        }

        parent::delete($id, $cascade);
    }

    /**
     * Creates a resource associated with a club, overwriting if necessary
     */
    public function saveForClub($clubId, $key, $fileUpload, $caption = null, &$error = null) {
        if (!is_array($fileUpload)) {
            $error = "No file was uploaded.";
            return false;
        }

        // Check for a duplicate resource and remove if necessary
        $preexistingResource = $this->find('first', array('conditions' => array('Resource.key' => $key, 'Club.id' => $clubId)));
        if ($preexistingResource != null) {
            $this->delete($preexistingResource['Resource']['id']);
        }

        $resource = array();
        $resource['club_id'] = $clubId;
        $resourceConfig = Configure::read("Resource.Club.$key");
        if ($resourceConfig == null) throw new InternalErrorException("The specified resource club key $key doesn't exist!");
        $resource['key'] = $key;
        $resource['caption'] = $caption;
        $resource['extension'] = $this->extensionOf($fileUpload['name']);
        $extensionError = $this->checkExtension($resource['extension'], $resourceConfig['allowedExtensions']);
        if ($extensionError) {
            $error = $extensionError;
            return false;
        }
        move_uploaded_file($fileUpload['tmp_name'], $this->absolutePathForResource($resource));

        $this->doThumbnailingIfNecessary($resource);

        return $this->save($resource);
    }

    public function doThumbnailingIfNecessary(&$resource) {
        if ($this->needsThumbnails($resource)) {
            $this->buildThumbnails($resource, $this->sizes());
        }
    }

    // Thumbnail sizes, in ImageMagick resize notation
    private function sizes() {
        return array('2600', '1300', '1000', '500', '100', '50');
    }

    private function extensionOf($file) {
        $path = pathinfo($file);
        return empty($path['extension']) ? null : strtolower($path['extension']);
    }

    private function buildThumbnails($resource, $sizes) {
        foreach($sizes as $size) {
            $this->buildThumbnail($resource, $size);
        }
    }

    private function needsThumbnails($resource) {
        return in_array($resource['extension'], $this->thumbnailableFiles);
    }

    private function buildThumbnail($resource, $size) {
        $source = $this->absolutePathForResource($resource);
        $destination = $this->absolutePathForResource($resource, $size);
        shell_exec("convert '$source' -resize $size\> '$destination'");
    }

    private function absolutePathForResource($resource, $thumbnail = null) {
        return Configure::read('Club.dir') . $this->relativePathForResource($resource, $thumbnail);
    }

    private function urlForResource($resource, $thumbnail = null) {
        return Configure::read('Club.dataUrl') . $this->relativePathForResource($resource, $thumbnail) . '?' . hash('md5', $resource['updated_at']);
    }

    private function relativePathForResource($resource, $thumbnail = null) {
        if ($thumbnail) {
            return $resource['key'] . '_' . $thumbnail . '.jpg';
        } else {
            return $resource['key'] . '.' . $resource['extension'];
        }
    }

    // Returns an error message if the file isn't allowed
    private function checkExtension($extension, $allowedExtensions) {
        if ($allowedExtensions == null) return null;

        if (in_array(strtolower($extension), $allowedExtensions)) {
            return null;
        }

        return "The given file extension: $extension, is not allowed. Provide a file with one of: ".implode(', ', $allowedExtensions);
    }
}
