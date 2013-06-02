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

    var $helpers = array("Time", "Geocode", "Form", "Leaflet", 'Media', 'Link');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'view', 'rendering', 'report');
    }

    function index() {
        $this->set('maps', $this->Map->find('all'));
        $this->set('edit', $this->isAuthorized(Configure::read('Privilege.Map.edit')));
    }

    function view($id = null) {
        $map = $this->Map->findById($id);
        if(!$map) {
            $this->Session->setFlash("The requested map couldn't be found.");
            $this->redirect('/');
            return;
        }
        $this->set('map', $map);
        $this->set('map_standard', $this->Map->MapStandard->findById(1));
        $this->set('events', $this->Map->Event->findAllByMapId($id));
        $this->set('view_ocad', 1);
        $this->set('edit', $this->isAuthorized(Configure::read('Privilege.Map.edit')));
    }

    function update($id, $lat, $lng) {
        $this->Map->set('lat', $lat);
        $this->Map->set('lng', $lng);
        $this->Map->save();
    }

    function download($id) {
        if(!$this->isAuthorized(Configure::read('Privilege.Map.view_ocad'))) {
            $this->Session->setFlash('You are not authorized to download this map.');
            $this->redirect('/maps/view/'.$id);
        }

        $map = $this->Map->findById($id);
        $this->viewClass = 'Media';

        // Get file from repository and store it in /tmp
        $file = tempnam(CACHE, "map_download_");
        $repoURL = Configure::read('Maps.repository.url');
        $command = "svn list " . $repoURL . $map["Map"]["repository_path"] . " --depth empty";
        $sys = system($command);
        if(!$sys){
            $this->Session->setFlash('File does not exist. Please contact webmaster.');
            $this->redirect('/maps/view/'. $id);
            return;
        } else {
            $command = "svn cat " . $repoURL . $map["Map"]["repository_path"] . " > $file";
            $sys = system($command);

            $name = basename($map['Map']['repository_path']);
            $this->response->type('application/octet-stream');
            $this->response->file($file, array(
                'download' => true,
                'name' => $name
            ));
            return $this->response;
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
        // Check permission
        $edit = $this->isAuthorized(Configure::read('Privilege.Map.edit'));
        if(!$edit) {
            if($id == null) {
                $this->Session->setFlash('You are not authorized to add a map.');
                $this->redirect('/maps/');
            } else {
                $this->Session->setFlash('You are not authorized to edit this map.');
                $this->redirect('/maps/view/'.$id);
            }
        }

        if($id != null)
            $this->set('map', $this->Map->findById($id));
        $this->set('mapStandards', $this->Map->MapStandard->find('list'));

        $this->Map->id = $id;
        if (empty($this->data)) {
            $this->data = $this->Map->read();
        } else {
            if ($this->Map->save($this->data)) {
                $this->Session->setFlash('The map has been updated.', "flash_success");

                if($this->request->data["Map"]["image"]["tmp_name"] != "") {
                    $this->Media->create($this->request->data['Map']['image'], $this->Map->id);
                    $this->generateBanner($id);
                }

                $this->redirect('/maps/view/'.$this->Map->id);
            }
        }
    }
}
