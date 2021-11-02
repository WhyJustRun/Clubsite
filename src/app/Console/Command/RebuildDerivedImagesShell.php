<?php
App::uses('ClubShell', 'Console/Command');

class RebuildDerivedImagesShell extends ClubShell {
	var $uses = array("Map", "Course", "Result", 'Resource');

	protected function runForClub() {
		$this->regenerateMediaImages("Map", "MapsController", array('Map.club_id' => Configure::read('Club.id')), false);
		$this->regenerateMediaImages("Course", "CoursesController", array('Event.club_id' => Configure::read('Club.id')), array('Event.club_id'));
		$this->resourceDerivedImages();

		// Regenerating the cropped images will change the images each time because they are randomly cropped to a position that looks good for the viewer.
		// $this->croppedMapImages();
	}
	
	private function regenerateMediaImages($modelName, $controllerName, $conditions, $contain) {		
		App::uses('AppController', 'Controller');

		// Standard Media component images
		App::uses($controllerName, 'Controller');
		$controller = new $controllerName();
		$controller->constructClasses();
		$model = $controller->$modelName;
		$mediaComponent = $controller->Media;
		$mediaComponent->initialize($controller);

		// Workaround for CakePHP not recreating the db connection if it times out on a long script run.
		$model->getDatasource()->reconnect();
		
		$objects = $model->find('all', array('contain' => $contain, 'conditions' => $conditions));
		$count = count($objects);
		$i = 0;
		foreach($objects as $object) {
			$mediaComponent->buildImages($object[$modelName]['id']);
			$i++;
			$percentage = round($i/$count * 100);
			echo "Regenerating $modelName derived images completed $percentage%\n";
		}
	}
	
	private function croppedMapImages() {
		App::uses('MapsController', 'Controller');
		$mapsController = new MapsController();
		$mapsController->constructClasses();
		$mediaComponent = $mapsController->Media;
		$mediaComponent->initialize($mapsController);

		// Workaround for CakePHP not recreating the db connection if it times out on a long script run.
		$mapsController->Map->getDatasource()->reconnect();
		$maps = $mapsController->Map->find('all', array('contain' => false, 'conditions' => array('Map.club_id' => Configure::read('Club.id'))));
		$count = count($maps);
		$i = 0;
		foreach($maps as $map) {
			$id = $map['Map']['id'];
			$thumbnailSize = "60x60";
			if($mediaComponent->exists($id, $thumbnailSize, null)) {
				$mediaComponent->createCroppedThumbnail($id, null, $thumbnailSize, "center");
				$i++;
				$percentage = round($i/$count * 100);
				echo "Regenerating cropped map derived images completed $percentage%\n";
			}
		}
	}
	
	private function resourceDerivedImages() {
		// Workaround for CakePHP not recreating the db connection if it times out on a long script run.
		$this->Resource->getDatasource()->reconnect();
		$resources = $this->Resource->findByClubId(Configure::read('Club.id'));
		foreach($resources as $resource) {
			$resourceOnly = $resource['Resource'];
			$this->Resource->doThumbnailingIfNecessary($resourceOnly);
			$data = array('Resource' => $resourceOnly);
			if(!$this->Resource->save($data)) {
				echo "Failed saving resource: $data";
			}
		}
	}
}
