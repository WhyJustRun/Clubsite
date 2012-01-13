<?php
App::uses('AppModel', 'Model');
/**
 * Resource Model
 *
 * @property Club $Club
 * @property Course $Course
 * @property Map $Map
 */
class Resource extends AppModel {
	public $displayField = 'caption';
	public $clubSpecific = false; // in general, not club specific, though *some* resources belong to a club directly (header image, logo, etc)

	public $belongsTo = array('Club', 'Course', 'Map');
	
    // Returns a map of club resource keys to urls, used by views
	public function forClub($clubId) {
        $clubResources = $this->findByClubId($clubId);
        $map = array();
	   
        if($clubResources != null) {
            foreach($clubResources as $resource) {
                $map[$resource['Resource']['key']] = $resource['Resource']['url'];
            }
        }
	   
        return $map;
	}
	
	public function findByClubId($clubId) {
	   return $this->find('all', array('conditions' => array('Club.id' => $clubId))); 
	}
	
	public function delete($id) {
	   $resource = $this->findById($id);
	   if(!empty($resource['Resource']['url'])) {
	       unlink($this->fileForUrl($resource['Resource']['url']));
	   }
	   
        $sizes = $this->sizes();
        
        foreach($sizes as $size) {
            if(!empty($resource['Resource']["thumbnail_${size}_url"])) {
                unlink($this->fileForUrl($resource['Resource']["thumbnail_${size}_url"])); 
            }
	   }
	   
	   parent::delete($id);
	}
	
	/**
	* Creates a resource associated with a club, overwriting if necessary
	*/
	public function saveForClub($clubId, $key, $fileUpload, $caption = null) {
	   if(!is_array($fileUpload)) {
	       throw new Exception("No file was uploaded.");
	   }
	   
	   // Check for a duplicate resource and remove if necessary
	   $preexistingResource = $this->find('first', array('conditions' => array('Resource.key' => $key, 'Club.id' => $clubId)));
	   if($preexistingResource != null) {
	       $this->delete($preexistingResource['Resource']['id']);
	   }
	   
	   $resource = array();
	   $resource['club_id'] = $clubId;
	   $resourceConfig = Configure::read("Resource.Club.$key");
	   if($resourceConfig == null) throw new Exception("The specified resource club key $key doesn't exist!");
	   $resource['key'] = $key;
	   $resource['caption'] = $caption;
	   $path = $this->pathForFile($resource['key'], $fileUpload, $resourceConfig['allowedExtensions']);
	   $resource['url'] = $this->storeFile($fileUpload, $path);
	   $sizes = $this->sizes();
	   
	   foreach($sizes as $size) {
	       $resource["thumbnail_${size}_url"] = Configure::read('Club.dataUrl').$this->pathForFile($key, $fileUpload, $resourceConfig['allowedExtensions'], $size);
	   }
	   
	   $this->buildThumbnails($resource, $sizes);
	   
	   if($this->save($resource)) {
	       return true;
	   } else return false;
	}

	// Thumbnail sizes, in ImageMagick resize notation
	private function sizes() {
	   return array('500', '50');
	}
	
	private function extensionOf($file) {
        $path = pathinfo($file);
        return strtolower($path['extension']);
    }
       
    private function fileForUrl($url) {
        return str_replace(Configure::read('Club.dataUrl'), Configure::read('Club.dir'), $url);
    }

    private function buildThumbnails(&$resource, $sizes) {
        foreach($sizes as $size) {
            $this->buildThumbnail($resource, $size);
        }
    }
    
    private function buildThumbnail(&$resource, $size) {
        $source = $this->fileForUrl($resource['url']);
        $destination = $this->fileForUrl($resource["thumbnail_${size}_url"]);
        shell_exec("convert '$source' -resize $size\> '$destination'");
    }

	private function pathForFile($name, $fileUpload, $allowedExtensions, $thumbnail = '', $directory = '') {
	   if($directory) $directory += "/";
	   if($thumbnail) {
	       $ext = 'jpg';
	       $thumbnail = "_".$thumbnail;
	   } else {
	       $ext = $this->extensionOf($fileUpload['name']);
	   }
	   $this->ensureDirectoryExists($directory);
	   $this->checkExtension($ext, $allowedExtensions);
	   return $directory.$name.$thumbnail.'.'.$ext;
	}
	
	private function ensureDirectoryExists($directory) {
	   $directory = Configure::read('Club.dir').'/'.$directory;
	   
	   if(!file_exists($directory)) mkdir($directory, 777, true);
	}

	// Throws an exception if the file isn't allowed
	private function checkExtension($extension, $allowedExtensions) {
	   if($allowedExtensions == null) return;
	   
	   if(in_array(strtolower($extension), $allowedExtensions)) {
	       return;
	   }
	   
	   throw new Exception("The given file extension: $extension, is not allowed. Provide a file with one of: ".implode(', ', $allowedExtensions));
	}
	
	// Path is relative to the Club directory
	private function storeFile($fileUpload, $path) {
	   move_uploaded_file($fileUpload['tmp_name'], Configure::read('Club.dir').$path);
	   return Configure::read('Club.dataUrl').$path;
	}
}
