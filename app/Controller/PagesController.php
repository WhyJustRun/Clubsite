<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       Cake.Controller
 * @link http://book.cakephp.org/view/958/The-Pages-Controller
 */
class PagesController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Pages';

    /**
     * Default helper
     *
     * @var array
     */
    public $helpers = array('Html', 'Session', 'Geocode', 'Markdown', 'FacebookGraph', 'Form', 'Media');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
        // Using jEditable so disable security checks for this controller
        $this->Security->csrfCheck = false;
        $this->Security->validatePost = false;
    }
    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     */
    public function display() {
        $path = func_get_args();

        $count = count($path);
        if (!$count) {
            if(!empty($_GET['id'])) {
                // for jeditable request
                $path[0] = $this->parseEntityId($_GET['id']);
                $this->set('onlyMarkdown', true);
                if(!empty($_GET['name'])) {
                    $this->set('field', 'name');
                } else $this->set('field', 'content');
            } else {
                $this->redirect('/');
            }
        }

        $page = $subpage = $title_for_layout = null;

        if (!empty($path[0])) {
            $page = $path[0];
            if($page === 'edit') {
                $this->params['action'] = 'edit';
                return $this->edit();
            } else if($page === 'add') {
                $this->params['action'] = 'add';
                return $this->add();
            } else if($page === 'delete') {
                $this->params['action'] = 'delete';
                return $this->delete($path[1]);
            }
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count - 1])) {
            $title_for_layout = Inflector::humanize($path[$count - 1]);
        }

        if($page === 'resources') {
            $this->set('pages', $this->Page->findAllBySection('resources', array('id', 'name')));
        }
        else if($page === 'admin') {
            $this->set('allowShowDuplicates', $this->isAuthorized(Configure::read('Privilege.User.edit')));
        }

        $dynamicPage = $this->Page->findById($page);

        if(!$dynamicPage) {
            $this->set(compact('page', 'subpage', 'title_for_layout'));
            $this->render(implode('/', $path));
        } else {
            $this->set('page', $dynamicPage);
            $this->set('title_for_layout', $dynamicPage['Page']['name']);
        }
    }

    public function add() {
        $this->checkAuthorization(Configure::read('Privilege.Page.edit'));
        if($this->request->isPost()) {
            // Section is hardcoded for now
            $this->request->data['Page']['section'] = 'Resources';
            if($this->Page->save($this->data)) {
                $this->Session->setFlash('The page has been added.', "flash_success");
            } else {
                $this->Session->setFlash('The page could not be added.');
            }
        }

        $this->redirect('/pages/resources');
    }

    public function edit() {
        $this->checkAuthorization(Configure::read('Privilege.Page.edit'));
        $this->Page->id = $this->parseEntityId($this->request->data['id']);
        if(!empty($this->request->data['value'])) {
            $this->Page->saveField('content', $this->request->data['value']);
            $this->set('content', $this->request->data['value']);
            $this->set('useMarkdown', true);
        } else if(!empty($this->request->data['name'])) {
            $name = $this->request->data['name'];
            $this->Page->saveField('name', $name);
            $this->set('content', $name);
            $this->set('useMarkdown', false);
        } else {
            // This should never happen, but somehow it does..
            $this->set('content', null);
            $this->set('useMarkdown', false);
        }

        $this->render('edit');
    }
    
    public function delete($id) {
        $this->checkAuthorization(Configure::read('Privilege.Page.delete'));
        $this->Page->delete($id);
        $this->redirect('/pages/resources');
    }

    private function parseEntityId($id) {
        $id = str_replace('page-resource-', '', $id);
        $id = str_replace('title-', '', $id);
        return $id;
    }
}
