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
    function ranking($end_date = NULL) {
        if($end_date == NULL) {
            $end_date = date('Y-m-d');
        }
        $end_date_int = strtotime($end_date);
        $start_date_int = strtotime($end_date) - 86400*365*2;
        $start_date = date('Y-m-d', $start_date_int);
        $start_date_format = date('M Y', $start_date_int);
        $end_date_format = date('M Y', $end_date_int);

        $rankings = $this->Result->meanPoints($start_date, $end_date);
        $rankingsAll = $this->Result->meanPoints(0, '3000-01-01');
        $this->set('rankings', $rankings);
        $this->set('rankingsAll', $rankingsAll);
        $this->set('start_date', $start_date_format);
        $this->set('end_date', $end_date_format);
    }

    function delete($id) {
        if($this->Result->isAuthorized($id, AuthComponent::user('id'))) {
            $this->Result->delete($id);
        }
    }
}
