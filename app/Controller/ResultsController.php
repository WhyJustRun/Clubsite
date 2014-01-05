<?php
class ResultsController extends AppController {

    var $name = 'Results';

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index');
        $this->Security->unlockedActions = array('editComment');
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

    function editComment() {
        if ($this->request->is('post') && !empty($this->request->data)) {
            $resultData = $this->request->data['Result'];
            $resultId = $resultData['id'];
            $resultComment = $resultData['comment'];

            if (empty($resultId)) {
                die("Must provide result id");
            }

            $contain = array('Course' => 'Event.id');
            $conditions = array('Result.id' => $resultId);
            $result = $this->Result->find('first', array('conditions' => $conditions, 'contain' => $contain));
            if (!empty($result)) {
                $userId = AuthComponent::user('id');
                if ($userId != $result['Result']['registrant_id'] && $userId != $result['Result']['user_id']) {
                    die("You are not allowed to change the comment for that result.");
                }

                $result['Result']['comment'] = $resultComment;
                $this->Result->save($result['Result']);
                $this->redirect('/events/view/' . $result['Course']['Event']['id']);

            }
        }
    }

    function delete($id) {
        if($this->Result->isAuthorized($id, AuthComponent::user('id'))) {
            $this->Result->delete($id);
        }
    }
}
