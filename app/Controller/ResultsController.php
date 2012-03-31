<?php
class ResultsController extends AppController {

	var $name = 'Results';

	function beforeFilter()
	{
		$this->Auth->allow('index', 'ranking');
	}

	function editRide() {
		if(!empty($this->request->data)) {
			$data = $this->request->data;
			if($this->Result->save($data)) {

			}
		}
		$this->redirect($this->referer());
	}

	function index() 
	{
		$results = $this->paginate();

		if (isset($this->params['requested'])) {
			return $results;
		} else {
			$this->set('results', $results);
		}
		
	}
	function ranking($date) {
		$rankings = $this->Result->meanPoints($date);
		$this->set('rankings', $rankings);
	}

	function delete($id) {
		if($this->Result->isAuthorized($id, AuthComponent::user())) {
			$this->Result->delete($id);
		}
	}
}
?>
