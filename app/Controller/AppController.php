<?php
App::uses('Controller', 'Controller');
class AppController extends Controller {
    // AppController's components are NOT merged with defaults,
    // so session component is lost if it's not included here!
    var $components = array('Auth' => array(
                                'authenticate' => array(
                                    'WhyJustRun'
                                )
                            ),
                            'Session',
                            'Security' => array(
                'csrfExpires' => '+12 hours',
                'csrfUseOnce' => false
                ));
    var $helpers = array('Session', 'User', 'Html', 'Menu', 'ContentBlock');


    function beforeFilter() {
        parent::beforeFilter();

        $this->Security->blackHoleCallback = 'blackholed';

        // Ensure the session is consistent with the cross app session
        if ($this->Session->check('CrossAppSession.id')) {
            $crossAppSessionID = $this->Session->read('CrossAppSession.id');
            $this->loadModel('CrossAppSession');
            $count = $this->CrossAppSession->find('count', array('conditions' => array('CrossAppSession.cross_app_session_id' => $crossAppSessionID)));

            if ($count != 1) {
                $this->Session->setFlash('You were signed out of your WhyJustRun account.');
                $this->Session->delete('CrossAppSession.id');
                $this->Auth->logout();
                $this->redirect('/', 302, false);
            }
        }

        // CakePHP bug: the Session Auth variables won't be set if $this->Auth->user() isn't called.
        $this->Auth->user();
        $this->Auth->loginRedirect  = array('controller' => 'pages', 'action' => 'display', 'home');
        // Doesn't seem to work: Manually set in Users Controller instead.
        $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display', 'home');
        $this->Auth->authError = "You need to sign in to do that.";

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
            $this->Session->setFlash('You are not authorized to access that page. Please switch to a different account or ask your club webmaster for permissions');
            $this->redirect('/');
        }
    }
}
