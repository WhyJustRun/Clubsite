<?php
class RebuildDerivedImagesShell extends Shell {
	var $uses = array("Map", "Course", "Result");

	function main() {
		$entities = array("Map" => "MapsController", "Course" => "CoursesController");
		
/*
		// Standard Media component images
		foreach($entities as $modelName => $controllerName) {
			App::uses($controllerName, 'Controller');
			$controller = new $controllerName();
			$controller->constructClasses();
			$model = $controller->$modelName;
			$mediaComponent = $controller->Media;
			$mediaComponent->initialize(&$controller);
			
			$objects = $model->find('all', array('contain' => false));
			$count = count($objects);
			$i = 0;
			foreach($objects as $object) {
				$mediaComponent->buildThumbnails($object[$modelName]['id']);
				$i++;
				$percentage = round($i/$count * 100);
				echo "Regenerating $modelName derived images completed $percentage%\n";
			}
		}
*/
		
		// Special cases
		
		App::uses('MapsController', 'Controller');
		$mapsController = new MapsController();
		$mapsController->constructClasses();
		$mediaComponent = $mapsController->Media;
		$mediaComponent->initialize(&$mapsController);
		$maps = $mapsController->Map->find('all', array('contain' => false));
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
}
?>
