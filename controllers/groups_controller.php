<?php

class GroupsController extends AppController {

    var $name = 'Groups';
    
    var $helpers = array('Html', 'Form', 'Javascript', 'Widgets', 'Tree');
    
    
    function index() {
        $groups = $this->Group->find('all', array('fields' => array('id', 'name',  'lft', 'rght'), 'order' => 'lft ASC')); 
        $this->set('groups', $groups);  
    }
    
    
    function add() {
        
    }
    
    
    function edit() {
        
    }
    
    
    function delete() {
        
    }
    
    
    function view() {
        
    }
    
    
}

?>
