<?php
class GenerateApiKeysShell extends Shell {
    public $uses = array('User');
    
    public function main() {
        $users = $this->User->find('all', array('recursive' => -1, 'conditions' => array('User.api_key' => null)));
        foreach($users as $user) {
            $user['User']['api_key'] = $this->User->generateApiKey();
            $this->User->save($user['User']);
        }
    }
}
?>