<?php
// There are some rare cases where we can't use CORS. This controller handles special proxy cases
class ProxiesController extends AppController {

    var $name = 'Proxies';

    public $components = array(
        'Security' => array(
            'validatePost' => false
        )
    );

    function beforeFilter() {
        parent::beforeFilter();
        $this->Security->unlockedActions = array('redactor');
    }

    // Redactor.js uses iframe file uploads which aren't CORS compatible.
    // type is either 'uploadFile' or 'uploadImage'
    function redactor($type) {
        $this->layout = null;
        if ($this->request->is('post')) {
            $crossAppSessionID = $this->Session->read('CrossAppSession.id');
            $url = Configure::read('Rails.containerDomain') . '/api/redactor/' . $type . ".json";
            $url .= '?cross_app_session_id=' . $crossAppSessionID;

            // Create a curl handle to upload to the file server
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            $file = new \CurlFile(
              $_FILES['file']['tmp_name'],
              $_FILES['file']['type'],
              $_FILES['file']['name']
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => $file));

            $result = curl_exec($ch);
            if ($result === false) {
                echo curl_error($ch);
                die("Failed uploading file.");
            }
            echo $result;
            curl_close($ch);
        } else {
            die("Only POST requests are supported");
        }
    }

}
