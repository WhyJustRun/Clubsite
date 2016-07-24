<?php
class UsersController extends AppController
{
    var $name = 'Users';
    var $helpers = array("Form", 'Recaptcha.Recaptcha', 'Link');
    var $components = array('Email', 'RequestHandler', 'Recaptcha.Recaptcha');

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'localLogin', 'logoutComplete', 'authorized', 'index');

        if($this->request->action == 'add') {
            $this->Security->csrfCheck = false;
            $this->Security->validatePost = false;
        }
    }

    function register() {
        $url = Configure::read('Rails.domain').'/users/sign_up';
        $this->redirect($url, 301, false);
    }

    function view($id) {
        $url = Configure::read('Rails.profileURL').$id;
        $this->redirect($url, 301, false);
    }

    function index() {
        // Autocomplete
        if(!empty($_GET["term"])) {
            $allowFake = true;
            if (!empty($_GET['allowFake']) && $_GET['allowFake'] === 'false') {
                $allowFake = false;
            }
            $this->set("users", $this->User->findByName($_GET["term"], 8, $allowFake));
        } else {
            $this->set("users", array());
        }
    }

    function localLogin()
    {
        if ($this->Auth->login()) {
            $sessionID = $this->request->query['cross_app_session_id'];
            if (!empty($sessionID)) {
                $this->Session->write('CrossAppSession.id', $sessionID);
            }

            $this->Session->setFlash('Sign in completed');
            if ($this->Session->check('User.lastPage')) {
                $this->Auth->loginRedirect = $this->Session->read('User.lastPage');
            }
            $this->redirect($this->Auth->redirect());
        } else {
            $this->Session->setFlash('Log in failed');
            $this->redirect('/');
        }
    }

    function login()
    {
        // Set the redirect if it is available and it is a redirect from the current domain, otherwise use the default
        if(!empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']) && !strstr(strtolower($_SERVER['HTTP_REFERER']), 'users/login')) {
            $this->Session->write('User.lastPage', $_SERVER['HTTP_REFERER']);
        } else {
            $this->Session->write('User.lastPage', $this->Auth->loginRedirect);
        }

        $url = Configure::read('Rails.loginURL');
        $url .= "?redirect_club_id=".Configure::read('Club.id');
        if ($this->Session->check('Message.auth')) {
            $flash = $this->Session->read('Message.auth');
            $url .= '&flash_message='.urlencode($flash['message']);
            $this->Session->delete('Message.auth');
        }
        $this->redirect($url);
    }


    function logout()
    {
        $this->Session->delete('CrossAppSession.id');
        $this->Auth->logout();
        $url = Configure::read('Rails.logoutURL');
        $url.= "?redirect_club_id=".Configure::read('Club.id');
        $this->redirect($url);
    }

    // The browser is redirected to this from rails
    function logoutComplete()
    {
        $this->redirect('/');
    }

    // Internally used by element: Event/add_link
    // Namespace conflict with Auth isAuthorized (that might be something we can use though)
    function authorized($authorizationLevel) {
        return $this->User->isAuthorized(AuthComponent::user('id'), $authorizationLevel);
    }

    function merge($targetId, $sourceId) {
        $this->checkAuthorization(Configure::read('Privilege.User.edit'));
        $field_options = array('year_of_birth'=>'target_source',
            'club_id'=>'target_source',
            'si_number'=>'target_source',
            'email'=>'target_source',
            'referred_from'=>'target_source');
        $this->User->merge($targetId, $sourceId, $field_options);
        $this->redirect('/users/showDuplicates');
        return;
    }

    function showDuplicates() {


        $edit_authorized = $this->isAuthorized(Configure::read('Privilege.User.edit'));
        // Merge is a lower privilege level than edit. Merge users are a allowed
        // to merge people associated with their club, whereas others can merge
        // anyone.
        $this->checkAuthorization(Configure::read('Privilege.User.merge'));

        if($this->request->is('post')) {
            $targetId = $this->data["User"][0]["user_id"];
            $sourceId = $this->data["User"][1]["user_id"];
            if($targetId != $sourceId) {
                $this->merge($targetId, $sourceId);
                $this->Session->setFlash("Users merged", 'flash_success');
            }
            else {
                $this->Session->setFlash('Users not merged');
            }
            $this->redirect('/users/showDuplicates');
            return;
        }
        $users = $this->User->find('list', array('order' => 'name asc', 'contain' => false));
        $this->set('users', $users);
        $dupUsers = $this->User->getDuplicates();
        $this->set('dupUsers', $dupUsers);
        $this->set('canMergeAnyUser', $edit_authorized);
    }

    /**
     * Add a simple user
     **/
    function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            $data = array();
            $data["User"]["name"] = $this->request->data["userName"];
            if(!$this->User->save($data, false)) {
                throw new InternalErrorException('Failed adding a user');
            }

            $this->set('userId', $this->User->id);
        }
    }
}
