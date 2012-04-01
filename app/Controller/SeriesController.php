<?php
class SeriesController extends AppController {

    var $name = 'Series';

    var $components = array('RequestHandler');
    var $helpers = array("Form", 'Markdown');

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index', 'view');
    }

    function index() 
    {
        $series = $this->Series->find('all', array('recursive' => -1));

        if (isset($this->params['requested'])) {
            return $series;
        } else {
            $this->checkAuthorization(Configure::read('Privilege.Series.edit'));
            $this->set('series', $series);
        }
    }

    function view($id) {
        //header('Content-Type: text/html');
        $this->set('series', $this->Series->findById($id)); 
        $this->set('events', $this->Series->Event->findAllBySeriesId($id)); 
        $this->set('organizers', $this->Series->findOrganizers($id)); 
        $this->set('start_date', $this->Series->startDate($id));
        $this->set('end_date', $this->Series->endDate($id));
    }

    function edit($id = null) {
        $this->checkAuthorization(Configure::read('Privilege.Series.edit'));
        $user = $this->Auth->user();

        // Check permissions
        $allow = 1;
        // FIXME-RWP Allow admins to edit
        if($allow === 0) {
            $this->Session->setFlash('You are not authorized to edit this page.');
            $this->redirect('/Series/view/'.$id);
        }

        $this->Series->id = $id;
        if (empty($this->data)) {
            $this->data = $this->Series->read();
        } else {
            if ($this->Series->save($this->data)) {
                $this->Session->setFlash('The serie has been updated.', "flash_success");
                //$this->redirect('/Series/view/'.$this->Series->id);
                $this->redirect('/Series/');
            }
        }
    }
}
?>
