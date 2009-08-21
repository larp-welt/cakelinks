<?php
/**
 * Ringsites Controller
 *
 * Provides us with a simple webring.
 * 
 * @author $Author: Marcus.Ertl $
 * @version $Rev: 62 $
 */ 

class RingsitesController extends AppController {
    var $name = 'Ringsites';
    
    var $helpers = array('Html', 'Cache', 'NiceHead', 'Paginator');
    
    var $cacheAction = false;
    
    /**
     * beforeFilter
     * 
     * Sets default values for Auth-Component and for pagination.
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
        $this->paginate = array('limit'=>Configure::read('Site.ItemsPerPage'));
    }
    
    function next($id) {
        /*
         * I'm don't using counterCache for counting hits.
         * I want to be able to delete old hits from the hits
         * table.
         */
        $this->Ringsite->id = $id;
        if (!$this->Ringsite->read()) {
            $this->cakeError('error404',array(array('action'=>'next')));
        }
        $next = $this->Ringsite->next();
        
        $this->Ringsite->countHit();

        if (empty($next['Ringsite']['url'])) {
            $this->redirect($next['Link']['url']);
        } else {
            $this->redirect($next['Ringsite']['url']);
        }
        exit();
    }
    
    
    function skip($id) {
        /*
         * I'm don't using counterCache for counting hits.
         * I want to be able to delete old hits from the hits
         * table.
         */
        $this->Ringsite->id = $id;
        if (!$this->Ringsite->read()) {
            $this->cakeError('error404',array(array('action'=>'skip')));
        }

        $next = $this->Ringsite->skip(2);
        $this->Ringsite->countHit();
        
        if (empty($next['Ringsite']['url'])) {
            $this->redirect($next['Link']['url']);
        } else {
            $this->redirect($next['Ringsite']['url']);
        }
        exit();
    }


    function prev($id) {
        /*
         * I'm don't using counterCache for counting hits.
         * I want to be able to delete old hits from the hits
         * table.
         */
        $this->Ringsite->id = $id;
        if (!$this->Ringsite->read()) {
            $this->cakeError('error404',array(array('action'=>'prev')));
        }
        $next = $this->Ringsite->prev();
        
        $this->Ringsite->countHit();
        
        if (empty($next['Ringsite']['url'])) {
            $this->redirect($next['Link']['url']);
        } else {
            $this->redirect($next['Ringsite']['url']);
        }
        exit();
    }
    
    
    function rand() {
        /*
         * I'm don't using counterCache for counting hits.
         * I want to be able to delete old hits from the hits
         * table.
         */
        $next = $this->Ringsite->rand();
        
        if (empty($next['Ringsite']['url'])) {
            $this->redirect($next['Link']['url']);
        } else {
            $this->redirect($next['Ringsite']['url']);
        }
        exit();
    }
    
    
    function members() {
        $this->redirect('/links/pages/webring');
    }
    
}

?>