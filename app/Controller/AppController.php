<?php
App::uses('Controller', 'Controller');
class AppController extends Controller {
    // AppController's components are NOT merged with defaults,
    // so session component is lost if it's not included here!
    var $components = array('Auth', 'Session', 'Security' => array(
        'csrfExpires' => '+12 hours',
        'csrfUseOnce' => false,
        'unlockedFields' => array('leaflet-base-layers'), // Leaflet includes a radio input in IE in POST requests if it is within the form.
    ));
    var $helpers = array('Session', 'Html', 'Menu', 'ContentBlock');


    function beforeFilter() {
        parent::beforeFilter();

        // CakePHP bug: the Session Auth variables won't be set if $this->Auth->user() isn't called.
        $this->Auth->user();
        $this->Auth->authenticate  = array('Form');
        $this->Auth->loginRedirect  = array('controller' => 'pages', 'action' => 'display', 'home');
        // Doesn't seem to work: Manually set in Users Controller instead.
        $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display', 'home');
        $this->Auth->authError = "You need to log in to do that";

        // Provide privileges to the views, and do some smart caching
        if(!$this->Session->check('Club.'.Configure::read('Club.id').'.Privilege') || $this->Session->read('Auth.User.id') != $this->Session->read('Privilege.User.id')) {
            $this->setPrivileges();
        }

        $this->setClubResources();

        if(array_key_exists('ext', $this->request->params) && $this->request->params['ext'] == 'embed') {
            $this->layout = 'embed';
        }
    }

    function setClubResources() {
        $this->loadModel('Resource');
        $this->set('clubResources', $this->Resource->forClub(Configure::read('Club.id')));
    }

    // Set user privileges for the Club
    function setPrivileges() {
        $this->Session->write('Privilege.User.id', $this->Session->read('Auth.User.id'));
        foreach(Configure::read('Privilege') as $entity => $privileges) {
            foreach($privileges as $key => $privilege) {
                $this->Session->write("Club.".Configure::read('Club.id').".Privilege.$entity.$key", $this->isAuthorized($privilege));
            }
        }
    }

    function isAuthorized($accessLevel) {
        $this->loadModel('User');
        return $this->User->isAuthorized(AuthComponent::user('id'), $accessLevel);
    }

    function checkAuthorization($accessLevel) {
        if(!$this->isAuthorized($accessLevel)) {
            $this->Session->setFlash('You are not authorized to access that page.');
            $this->redirect('/users/login');
        }
    }
}

?>
