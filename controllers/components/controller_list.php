<?php

// http://cakebaker.42dh.com/2006/07/21/how-to-list-all-controllers/
class ControllerListComponent extends Object {
    function get() {
     	$controllerClasses = Configure::listObjects('controller');

        foreach($controllerClasses as $controller) {
            if ($controller != 'App' && $controller != 'Pages') {
                $fileName = Inflector::underscore($controller).'_controller.php';
                $file = CONTROLLERS.$fileName;
                require_once($file);
                $className = $controller . 'Controller';
                $actions = get_class_methods($className);
                foreach($actions as $k => $v) {
                    if ($v{0} == '_') {
                        unset($actions[$k]);
                    }
                }
                $parentActions = get_class_methods('AppController');
                $controllers[$controller] = array_diff($actions, $parentActions);
            }
        }

        return $controllers;
    }
}

?>