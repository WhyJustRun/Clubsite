<?php
App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class WhyJustRunAuthenticate extends BaseAuthenticate {
    public function authenticate(CakeRequest $request, CakeResponse $response) {
        $sessionID = $request->query['cross_app_session_id'];
        if (empty($sessionID)) {
            return false;
        }
        return $this->userForSessionID($sessionID); 
    }

    private function userForSessionID($sessionID) {
        $dataSource = ConnectionManager::getDataSource('default');
        $safeSessionID = Sanitize::escape($sessionID, 'default');
        $sql = "SELECT user_id FROM cross_app_sessions WHERE cross_app_session_id = '$safeSessionID'";
        $results = $dataSource->fetchAll($sql);
        if (count($results == 1)) {
            $userID = $results[0]['cross_app_sessions']['user_id'];
            $user = $this->_findUser(array('User.id' => $userID));
            return $user;
        } else {
            return false;
        }
    }
}
?>

