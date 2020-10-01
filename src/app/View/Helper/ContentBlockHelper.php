<?php
class ContentBlockHelper extends AppHelper {
    private $cachePrefix = "content_block_";
    private $cacheStore = 'default';
    public $helpers = array('User');

    public function render($key, $start = null, $end = null) {
        $editableClass = '';
        if ($this->User->canEditContentBlock()) {
            $editableClass = ' wjr-editable';
        }
        $cacheKey = $this->getCacheKey($key, $start, $end);
        $content = Cache::read($key, $this->cacheStore);
        if(!$content) {
            $blocks = $this->getBlocks($key);
            $content = null;
            foreach($blocks as $block) {
                $content .= $start;
                $content .= "<div id=\"" . $this->getId($block) . "\" class=\"content-block" . $editableClass . "\">";
                $content .= $block['ContentBlock']['content'];
                $content .= "</div>";
                $content .= $end;
            }
            Cache::delete($cacheKey, $this->cacheStore);
            Cache::write($cacheKey, $content, $this->cacheStore);
        }

        return $content;
    }

    private function getId($block) {
        return "content-block-" . $block["ContentBlock"]['id'];
    }

    private function getBlocks($key) {
        $blocks = $this->requestAction("/contentBlocks/view/$key");

        return $blocks;
    }

    private function getCacheKey($key, $start, $end) {
        $key = $this->cachePrefix . $key . '-' . md5($start.$end);
        if ($this->User->canEditContentBlock()) {
            $key .= "-editable";
        }
        return $key;
    }
}
