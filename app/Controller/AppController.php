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
        $this->setClubProperties();
        $this->Security->blackHoleCallback = 'blackholed';
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
        } else {
            // Set layout based on club's layout parameter
            $this->layout = Configure::read('Club.layout');
        }
    }
    
    function blackholed($type) {
	    CakeLog::error("Request was blackholed of type: $type");
	    $this->Session->setFlash('An error occurred, email: support@whyjustrun.ca.');
	    $this->redirect('/');
    }
    
    function setClubProperties() {
        if (!Configure::read('Club.loadedFresh')) {
            $this->loadModel('Club');
            $club = $this->Club->find('first', array('recursive' => -1, 'conditions' => array('Club.id' => Configure::read('Club.id'))));
            Configure::write('Club.loadedFresh', true);
            foreach($club['Club'] as $key => $value) {
                if ($key === 'timezone') {
                    $value = timezone_open($value);
                }
                Configure::write('Club.'.$key, $value);
            }
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
                $key = "Club.".Configure::read('Club.id').".Privilege.$entity.$key";
                $this->Session->write($key, $this->isAuthorized($privilege));
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
