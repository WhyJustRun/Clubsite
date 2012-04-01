<?php
class MapsController extends AppController {

	var $name = 'Maps';

	var $components = array(
	   'RequestHandler',
	   'Media' => array(
	       'type' => 'Map',
	       'allowedExts' => array('jpg', 'jpeg', 'gif', 'png'),
	       'thumbnailSizes' => array('400x600', '50x50'),
	       'thumbnailExt' => 'gif'
	   )
    );
    
	var $helpers = array("Time", "Geocode", "Form", "Leaflet", 'Media');

    function beforeFilter() {
        parent::beforeFilter();
		$this->Auth->allow('index', 'view', 'rendering', 'report');
	}
	
	function index() {
        $this->loadModel('User');
        $this->set('maps', $this->Map->find('all'));
        $this->set('edit', $this->User->isAuthorized(AuthComponent::user('id'), Configure::read('Privilege.Map.edit')));
    }

	function view($id) {
        $this->loadModel("MapStandard");
        $this->loadModel("Event");
        $this->loadModel("Group");
        $this->set('map', $this->Map->findById($id));
		$this->set('map_standard', $this->MapStandard->findById(1));
		$this->set('events', $this->Event->findAllByMapId($id));

        $this->loadModel('User');
		$this->set('view_ocad', $this->User->isAuthorized(AuthComponent::user('id'), Configure::read('Privilege.Map.view_ocad')));
		$this->set('edit', $this->User->isAuthorized(AuthComponent::user('id'), Configure::read('Privilege.Map.edit')));
	}

    function update($id, $lat, $lng) {
        $this->Map->set('lat', $lat);
        $this->Map->set('lng', $lng);
        $this->Map->save();
    }
   
    function download ($id) {
        // Check permission
        $this->loadModel('User');
		$view_ocad = $this->User->isAuthorized(AuthComponent::user('id'), Configure::read('Privilege.Map.view_ocad'));
		if(!$view_ocad) {
			$this->Session->setFlash('You are not authorized to download this map.');
			$this->redirect('/Maps/view/'.$id);
		}

        $map = $this->Map->findById($id);
        $this->viewClass = 'Media';
        
        // Get file from repository and store it in /tmp
        $file = tempnam("/tmp", "wjr_");
        $command = "svn list file:///var/svn/gvoc" . $map["Map"]["repository_path"] . " --depth empty";
        $sys = system($command);
        if(!$sys){
            $this->Session->setFlash('File does not exist. Please contact webmaster.');
            $this->redirect('/Maps/view/'. $id);
            return;
        } else {
            $command = "svn cat file:///var/svn/gvoc" . $map["Map"]["repository_path"] . " > $file";
            $sys = system($command);
            
            $params = array(
                'id' => $file,
                'name' => basename($map["Map"]["repository_path"]),
                'download' => true,
                'extension' => 'ocd',
                'mimeType' => array(
                   'ocad' => 'application/octet-stream'
                ),
                'path' => '/'
            );
            $this->set($params);
        }
   }
   
    // Displays a rendering of the OCAD file (manually uploaded)
    function rendering($id, $thumbnail = false) {
        $this->Media->display($id, $thumbnail);
    }

    function generateBanner($id) {
        $this->Media->createCroppedThumbnail($id, 'Map', '60x60');
        $this->redirect("/maps/edit/$id");
        return;
    }
   
    function delete($id) {
        $this->checkAuthorization(Configure::read('Privilege.Map.delete'));
        if(!empty($id)) {
            $this->Map->delete($id);
            $this->Media->delete($id, "Map");
            $this->Session->setFlash('The map was deleted.', 'flash_success');
        }
        else {
            $this->Session->setFlash('No map id provided.');
        }
        $this->redirect("/maps/");
    }
    function report() {
        $maps = $this->Map->find('all');
        $maps = @Set::sort($maps, "{n}.Map.name", 'asc');
        $this->set('maps', $maps);
    }
   
   function edit($id = null) {
		$user = $this->Auth->user();

      // Check permission
      $this->loadModel('User');
		$edit = $this->User->isAuthorized(AuthComponent::user('id'), Configure::read('Privilege.Map.edit'));
		if(!$edit) {
         if($id == null) {
   			$this->Session->setFlash('You are not authorized to add a map.');
   			$this->redirect('/Maps/');
         }
         else {
   			$this->Session->setFlash('You are not authorized to edit this map.');
   			$this->redirect('/Maps/view/'.$id);
         }
		}

      if($id != null)
   		$this->set('map', $this->Map->findById($id));
		$this->set('mapStandards', $this->Map->MapStandard->find('list'));

		$this->Map->id = $id;
		if (empty($this->data)) {
			$this->data = $this->Map->read();
      }
      // Process
      else {
        if ($this->Map->save($this->data)) {
            $this->Session->setFlash('The map has been updated.', "flash_success");

            if($this->request->data["Map"]["image"]["tmp_name"] != "") {
                $this->Media->create($this->request->data['Map']['image'], $this->Map->id);
            }

            $this->redirect('/Maps/view/'.$this->Map->id);
			}
        }
    }
}
?>
