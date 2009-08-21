<?php
/*
 * Cached ACL
 *
 * Cakes ACL doesn't cache anything. For better performance, we
 * put results of check into session.
 */
class CachedAclComponent extends DbAcl {


    function initialize(&$controller) {
        $this->master =& $controller;
        App::import('component', 'Session');
        $this->Session = new SessionComponent();
    }


    function check($aro, $aco, $action = "*") {
        $key = str_replace(array('/', '*'),
                           array('.', 'all'),
                           strtolower('CachedAcl.'.$aco.'.'.$action));
        if ($this->Session->check($key)) {
            return $this->Session->read($key);
        } else {
            $result = parent::check($aro, $aco, $action);
            $this->Session->write($key, $result);
            return $result;
        }
    }
}

?>