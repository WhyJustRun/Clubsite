<?php
App::uses('AppController', 'Controller');
/**
 * ContentBlocks Controller
 *
 * @property ContentBlock $ContentBlock
 */
class ContentBlocksController extends AppController {
    public $components = array(
        'RequestHandler'
    );
    public $helpers = array('Markdown');

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('view');
    }

    /**
     * Called by the ContentBlockHelper
     */    
    public function view($key = null) {
        if(empty($key)) {
            // called from Jeditable for in place editing
            $id = str_replace('content-block-', '', $_GET['id']);
            $this->set('contentBlock', $this->ContentBlock->findById($id));
        } else {
            $contentBlocks = $this->ContentBlock->findAllByKey($key, array(), array('ContentBlock.order' => 'asc'));
            if(count($contentBlocks) === 0) {
                // If we didn't find any content blocks, create them from the defaults
                $contentBlocks = Configure::read('ContentBlock.default.'.$key);
                if($contentBlocks == null) {
                    return array();
                }
                foreach($contentBlocks as $order => $content) {
                    $this->ContentBlock->create();
                    $this->ContentBlock->save(array('ContentBlock' => array('key' => $key, 'order' => $order, 'content' => $content)));
                    $contentBlocks = $this->ContentBlock->findAllByKey($key, array(), array('ContentBlock.order' => 'asc'));
                }
            }
            return $contentBlocks;
        }
    }

    /**
     * POST Ajax call by Jeditable - returns the rendered markdown
     */
    public function edit() {
        $this->checkAuthorization(Configure::read('Privilege.ContentBlock.edit'));
        $this->ContentBlock->id = str_replace('content-block-', '', $this->request->data['id']);
        $this->ContentBlock->saveField('content', $this->request->data['value']);
        $this->set('contentBlock', $this->ContentBlock->findById($this->ContentBlock->id));
    }
}
