<?php
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController 
{

    var $name = 'Users';
    var $helpers = array("Form", 'Recaptcha.Recaptcha');
    var $components = array('Email', 'RequestHandler', 'Recaptcha.Recaptcha');

    function beforeFilter()
    {
        $this->Auth->allow('register', 'forgot', 'login', 'verify', 'authorized', 'index', 'view');
        parent::beforeFilter();
    }

    function index() {
        // Autocomplete
        if(!empty($_GET["term"])) {
            $conditions = array('User.name' => $_GET["term"]);
            $this->set("users", $this->User->findByName($_GET["term"]));
        } else {
            $this->set("users", array());
        }
    }

    function login() 
    {
        $this->set('title_for_layout', 'Login/Register');
        if ($this->request->is('post')) {
            $loggedIn = false;
            if ($this->Auth->login()) {
                $loggedIn = true;
            } else {
                // Try logging in with truncated password
                // NOTE-RWP Remove this code sometime in 2012, as it means passwords only have 6 characters of entropy in the meantime
                $truncatedData = $this->request->data;
                $truncatedData['User']['password'] = AuthComponent::password(substr($truncatedData['User']['password'], 0, 6));

                $user = $this->User->findByUsername($this->request->data["User"]["username"]);
                if(!empty($user["User"]["id"]) && $truncatedData['User']['password'] === $user["User"]["password"]) {
                    $this->User->id = $user["User"]["id"];
                    // Overwrite password
                    CakeLog::write('debug', $user["User"]["username"] . " overwrote their password (a 6 character truncation of their input matched the saved password");
                    $this->User->saveField('password', AuthComponent::password($this->request->data["User"]["password"]));
                }

                if($this->Auth->login()) {
                    $loggedIn = true;
                }
            }
            if($loggedIn) {
                $this->Session->write("loggedIn", true);
                if($this->Session->check('User.lastPage')) {
                    $this->Auth->loginRedirect = $this->Session->read('User.lastPage');
                }
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
            }
        }

        // Set the redirect if it is available and it is a redirect from the current domain, otherwise use the default
        if(!empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']) && !strstr(strtolower($_SERVER['HTTP_REFERER']), 'users/login')) {
            $this->Session->write('User.lastPage', $_SERVER['HTTP_REFERER']);
        } else {
            $this->Session->write('User.lastPage', $this->Auth->loginRedirect);
        }
    }


    function logout()
    {
        $this->Auth->logout();
        $this->redirect('/');
    }

    function edit() 
    {
        $this->request->data["User"]["id"] = AuthComponent::user('id');
        if(empty($this->request->data)) {

        } else {
            $this->User->set($this->request->data);
            //$this->request->data["User"]["password"] = AuthComponent::password($this->request->data["User"]["password"]);
            if($this->User->save($this->request->data, array('validate' => true, 'fieldList'=>array('name', 'email', 'year_of_birth', 'si_number')))) {
                foreach ($this->request->data["User"] as $key=>$value) {
                    $this->Session->write("Auth.User.$key",$value);
                }
                $this->Session->setFlash('Settings changed', 'flash_success');  
            }
        }
        $id = $this->Auth->user('id');
        $this->set('is_subscribed', $this->_isSubscribed($id));
        $this->redirect('/users/view/'.$id);

    }
    // Determine if user is subscribed to the e-mailing list
    function _isSubscribed($id) {
        $this->User->id = $id;
        $emailAddress = $this->User->field('email');
        $string = "/usr/bin/python /usr/sbin/list_members gvoc | grep $emailAddress";// /tmp/wjr.check_subscription.error";
        $q = shell_exec($string);
        if($q == "")
            return false;
        else {
            return true;
        }
    }
    // Internally used by element: Event/add_link
    // Namespace conflict with Auth isAuthorized (that might be something we can use though)
    function authorized($authorizationLevel) {
        return $this->User->isAuthorized(AuthComponent::user('id'), $authorizationLevel);
    }

    function view($id = null) {
        $user = $this->User->find('first', array('conditions' => array('User.id' => $id), 'recursive' => -1));
        if(!$user) {
            $this->Session->setFlash("The requested profile couldn't be found.");
            $this->redirect('/');
            return;
        }

        if($this->request->data('User.message') != null) {
            $this->message();
        }

        $this->set('show_settings', false);
        $this->set('show_info', false);
        $this->set('show_membership', false);
        // User is allowed to see their own settings
        if ($this->Auth->user('id') == $id) {
            $this->set('show_settings', true);
            //$this->set('memberships', $this->User->Membership->findAllByUserId($id));
            //$this->set('show_membership', true);
            $this->set('is_subscribed', $this->_isSubscribed($id));
        }

        $this->set('title_for_layout', $user["User"]["name"]);
        $this->set('user', $user);
        $results = $this->User->Result->find('all', array( 'conditions' => array('Result.user_id' => $id), 'contain' => array('Course' => array('Event')), 'order'=>array('Result.points DESC')));
        $this->set('results', $results);

        if(!empty($results)) {
            $results = Set::sort($results, '{n}.Course.Event.date', 'asc');
        }

        $jsonResults = array();
        foreach($results as $result) {
            if(!empty($result["Result"]["points"])) {
                $jsonResult = array();
                $date = new DateTime($result["Course"]["Event"]['date']);
                $jsonResult["x"] = $date->getTimestamp()*1000;
                $jsonResult["y"] = intval($result["Result"]["points"]);
                $jsonResult["name"] = $result["Course"]["Event"]['name'] . " (".$result["Course"]['name'].") - Time: " . $result["Result"]["time"];
                array_push($jsonResults, $jsonResult);
            }
        }
        $this->set('jsonResults', json_encode($jsonResults));  
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
        $this->checkAuthorization(Configure::read('Privilege.User.edit'));
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
        $users = $this->User->find('list', array('order' => 'name asc'));
        $this->set('users', $users);
        $dupUsers = $this->User->getDuplicates();
        $this->set('dupUsers', $dupUsers);
    }

    /**
     * Add a simple user
     **/
    function add() {
        if ($this->request->is('post')) {
            if($this->User->Organizer->isAuthorized($this->request->data["eventId"], AuthComponent::user('id'))) {
                $this->User->create();
                $data = array();
                $data["User"]["name"] = $this->request->data["userName"];
                if(!$this->User->save($data, false)) {
                    throw new InternalErrorException('Failed adding a user');
                }
            } else {
                throw new ForbiddenException('You are not authorized to add a user.');
            }
        }
    }

    function forgot()
    {
        $this->set("title_for_layout","Recover Password");
        if ($this->Auth->user()) {
            $this->redirect('/');
        }

        if (!empty($this->request->data['User']['email'])) {
            $users = $this->User->find('all', array('recursive' => -1, 'conditions' => array('email' => $this->request->data['User']['email'])));
            $names = array();
            if (count($users) === 0) {
                $this->Session->setFlash('No matching account with the email "' . $this->request->data['User']['email'] . '" was found.');
                $this->redirect('/users/login');
            }

            $tokenGenerator = ClassRegistry::init('Token');
            foreach($users as $user) {
                $token = $tokenGenerator->generate(array('User' => $user['User']));
                $email = new CakeEmail('default');
                $email->emailFormat('text');
                $email->template('forgot', 'default');
                $email->viewVars(array('token' => $token, 'user' => $user));
                $email->subject("Password Recovery");
                $email->from("noreply@whyjustrun.ca", Configure::read('Club.name'));
                $email->to($this->request->data['User']['email']);
                $email->send();

                $names[] = $user["User"]["name"];
            }
            if(count($users) > 1) {
                $this->Session->setFlash('Emails have been sent to your email account (' . $user['User']['email'] . ') for each user associated with this email ('.implode(", ", $names).'). Follow the instructions in the email to reset your password.');
            } else {
                $this->Session->setFlash('An email has been sent to your email account (' . $user['User']['email'] . '), please follow the instructions in the email.');
            }
        }

        $this->redirect('/');
    }
    function subscribe() {
        $this->_subscribe();
        $this->Session->setFlash('You have been subscribed', 'flash_success');
        $this->redirect('/users/edit/');
    }

    function _subscribe() {
        $id = $this->Auth->user('id');
        $this->User->id = $id;
        $emailAddress = $this->User->field('email');
        $string = "echo $emailAddress > /tmp/mailman.adding; /usr/bin/python /usr/sbin/add_members -r /tmp/mailman.adding gvoc 2> /tmp/wjr.subscribe.error";
        $q = shell_exec($string);
    }

    function unsubscribe() {
        $id = $this->Auth->user('id');
        $this->User->id = $id;
        $emailAddress = $this->User->field('email');
        $string = "/usr/bin/python /usr/sbin/remove_members gvoc $emailAddress 2> /tmp/wjr.unsubscribe.error";
        $q = shell_exec($string);
        $this->Session->setFlash('You have been unsubscribed', 'flash_success');
        $this->redirect('/users/edit/');
    }

    function register()
    {
        $this->set('title_for_layout', 'Create an Account');
        $this->set('clubs', $this->User->Club->find('list'));

        if(!empty($this->request->data)) {
            if($this->Recaptcha->verify()) {
                $this->User->create();

                // Check if temporary account already exists
                $existingUser = $this->User->findTemporaryByName($this->request->data['User']['name']);
                if($existingUser != "") {
                    // If so, commandeer this account
                    $this->User->id = $existingUser["User"]["id"];
                }

                $data = $this->request->data;
                $data['User']['password'] = $this->User->hashPassword($data['User']['password']);
                if($this->User->save($data)) {
                    $this->Auth->login();
                    $this->Session->setFlash('Welcome!', 'flash_success');
                    return $this->redirect($this->Auth->redirect());
                }
            } else {
                // display the raw API error
                $this->Session->setFlash($this->Recaptcha->error);
            }
        }
    }

    /**
     * Send a message to a user via email.
     */
    function message() {
        if(!empty($this->request->data)) {
            if($this->Recaptcha->verify()) {
                $user = $this->User->findById($this->request->data['User']['id']);
                $replyTo = $this->Session->read('Auth.User.email');
                if(!empty($user) && !empty($user['User']['email']) && !empty($replyTo)) {
                    $email = new CakeEmail('default');
                    $email->emailFormat('text');
                    $email->template('message', 'default');
                    $email->viewVars(array('message' => $this->request->data['User']['message'], 'from' => $this->User->findById($this->Session->read('Auth.User.id')), 'to' => $user));
                    $email->subject("Message from ".$this->Session->read('Auth.User.name'));
                    $email->from("noreply@whyjustrun.ca", Configure::read('Club.name'));
                    $email->replyTo($replyTo, $this->Session->read('Auth.User.name'));
                    $email->to($user['User']['email']);
                    $email->send();
                    $this->data = null;
                    $this->Session->setFlash('Message sent!', 'flash_success');
                } else {
                    $this->Session->setFlash('Message sending failed.');
                }
            } else {
                $this->Session->setFlash('Captcha validation failed.');
            }
        }
    }

    /**
     * Accepts a valid token and resets the users password
     *
     * @param unknown $token (optional)
     */
    function verify($token = null) {
        $this->set("title_for_layout","Verify password recovery");
        if ($this->Auth->user()) {
            $this->Auth->logout();
        }
        $tokenObject = ClassRegistry::init('Token');

        // set from form if url token is empty
        if(empty($token)) {
            $token = $this->request->data['User']['token'];
        }

        $user = $tokenObject->get($token);
        if(empty($token) || empty($user)) {
            $this->Session->setFlash('An error occurred. Please try resetting your password again.');
            $this->redirect('/users/login');
        }

        $this->set('token', $token);
        $this->set('user', $user);

        if ($this->request->is('post')) {
            // Update password if they match.
            if ($this->_passwordsOk() && $tokenObject->existsByToken($token)) {
                $data = $tokenObject->get($token);
                // After new password view
                // Update the users password
                App::import('Vendor', 'password');

                // Save password
                $this->User->id = $data['User']['id'];
                $this->User->saveField('password', AuthComponent::password($this->request->data['User']['password']));

                // Delete token
                $data = $tokenObject->get($token, true);

                $data['User']['password'] = $this->data['User']['password'];
                $this->request->data = $data;
                if(!$this->login()) {
                    $this->Session->setFlash('Failed logging in');
                    $this->redirect('/');
                }

                if ($this->Auth->loggedIn()) {
                    $this->Session->setFlash('Password changed successfully!', 'flash_success');  
                    $this->redirect('/');
                }
                // Show password change view if token is set and valid
            } else if(!empty($token) && $tokenObject->existsByToken($token)) {
                // If new passwords don't match
                if(!empty($this->data['User']['token']) && !$this->_passwordsOk()) {
                    // Don't display hashed password in form
                    $this->data = null;
                    $this->Session->setFlash('Your new passwords didn\'t match.');
                }
            } else {
                // If no token specified
                $this->Session->setFlash('Please try resetting your password again.');
                $this->redirect('/users/login');
            }
        }
    }

    function _passwordsOk() {
        if(!empty($this->data['User']['password']) && !empty($this->request->data['User']['password_confirm']) && $this->request->data['User']['password_confirm'] === $this->request->data['User']['password']) {
            return true;
        } else {
            return false;
        }
    }

    function edit_change_password()
    {
        if(!empty($this->request->data)) {
            $this->User->set($this->request->data);

            // Check if new passwords match
            if($this->request->data['User']['password'] == $this->request->data['User']['new_password2']) {
                $this->request->data['User']['password'] = $this->User->hashPassword($this->request->data['User']['password']);
                if($this->User->save($this->request->data, array('validate' => true, 'fieldList'=>array('password')))) {
                    $this->Session->setFlash('Password changed', 'default', array('class'=>'success'));
                    $this->redirect('/users/edit/');
                }
                else {
                    $this->Session->setFlash('New password not valid');
                    $this->redirect('/users/edit/');
                }
            }
            else {
                $this->Session->setFlash('Passwords do not match');
                $this->redirect('/users/edit/');
            }
        }
        $this->redirect('/users/edit/');
    }
}
?>
