<?php
/*
 * Track Component
 *
 * Add current page/action to the breadcrumbs.
 */

class TracksComponent extends Object {
    var $components = array('Session');

    var $_breadname = 'breadcrumbs';
    var $_max = 5;
    /*
     * beforeRender
     *
     * Adds the currend page/action to the breadcrumbs list.
     */

    function beforeRender(&$controller) {
        $tracks = $this->Session->read($this->_breadname);
        if (!is_array($tracks)) $tracks = array();
        $here = array($controller->here, $controller->pageTitle);
        if (!in_array($here, $tracks) && !empty($controller->pageTitle)) $tracks[] = $here;
        while (count($tracks) > $this->_max) array_shift($tracks);
        $this->Session->write($this->_breadname, $tracks);
    }
}
?>
