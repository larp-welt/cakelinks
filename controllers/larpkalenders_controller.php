<?php

class LarpkalendersController extends AppController {
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }
    
    function index() {
        $data = $this->Larpkalender->find('all');
        return $data['items'];
    }
}


?>
