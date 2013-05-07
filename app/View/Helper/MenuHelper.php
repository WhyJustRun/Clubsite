<?php
// Originally from: http://www.solitechgmbh.com/2008/06/06/menu-highlighting-with-cakephp-12/, substantially improved upon
class MenuHelper extends Helper {
    var $helpers = array('Html', 'Link');

    function item($name, $url, $class='', $home = false) {
        return "<li class='".$this->highlight($url, $home). " " . $class . "'>"
            . $this->Html->link($name, $url)
            . "</li>";
        }

/**
 * Highlight a menu option based on path
 *
 * A menu path gets passed and it compares to requested path and sets the call to be highlighted.
 * Use regular expressions in the pattern matching.
 *
 * @param path for which the nav item should be highlighted
 * @param optional normal class to be returned, default ''
 * @param optional highlight class to be returned, default 'selected'
 * @return returns the proper class based on the url
 */
        function highlight($path, $home = false, $normal = '', $selected = 'active') {
            $class = $normal;
            $currentPath = substr($this->Html->here, strlen($this->Html->base));
            // if there is a star in the path we need to do different checking
            $regs = array();
            if($home) {
                if($currentPath === '' || $currentPath === '/pages/home' || $currentPath === '/')
                    $class = $selected;
                } else if (!$home && ereg($path,$currentPath,$regs)){
                    $class = $selected;
                }

                return $class;
    }
}
