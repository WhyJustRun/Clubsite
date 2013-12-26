<?php
class ResultsController extends AppController {

    var $name = 'Results';

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index', 'ranking');
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

    function delete($id) {
        if($this->Result->isAuthorized($id, AuthComponent::user('id'))) {
            $this->Result->delete($id);
        }
    }
}
