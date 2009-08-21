<?php

//    
// $Rev:: 26                    $:  Revision of last commit
// $Author:: Marcus.Ertl        $:  Author of last commit
// $Date:: 2008-07-31 20:06:13 #$:  Date of last commit
// 

class CommentsController extends AppController {
    var $name = 'Comments';
    
    var $uses = array('Comment', 'User');
    var $helpers = array('Html', 'Form', 'Javascript', 'Widgets', 'Cache', 'NiceHead');
    
    var $cacheAction = false;
    
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'latest');
        $this->paginate = array('limit'=>Configure::read('Site.ItemsPerPage'),
                                'order'=>'Comment.created DESC');
    }
    
    
    function index($model, $id) {
            /* Unused function */
            $comments= $this->paginate('Comment', array( 'Comment.parent_id'=>$id,
                                                         'Comment.parent_model'=>$model,
                                                         array('or'=>array(array('Comment.status'=>ACTIVE), 
                                                                     array('Comment.status'=>WARNING)))));
            if (isset($this->params['requested'])) {
                return $comments;
            } else {
                $this->set(compact('comments'));
            } 
    }
    
    
    function add($model=null, $parent=null) {
      if (!empty($this->data)) {
            $user = $this->Auth->user();
            $this->Comment->create();
            $this->data['Comment']['user_id'] = $user['User']['id'];
            $this->data['Comment']['status'] = Configure::read('Comment.StartStatus');
            
            $singular = Inflector::singularize($this->data['Comment']['parent_model']);
            $plural = $this->data['Comment']['parent_model'];
            
            $this->Comment->bindModel(array('belongsTo'=>array($singular=>
                                                  array('counterCache'=>true,
                                                        'foreignKey'=>'parent_id',
                                                        'conditions'=>array('parent_model'=>$plural)))));
            if ($this->Comment->save($this->data)) {
                $this->Session->setFlash('Ihr Kommentar wurde gesichert',
                                         'default', array('class'=>'ok'));
                $this->redirect(array('action'=>'view/'.$this->data['Comment']['parent_id'], 
                                      'controller'=>$this->data['Comment']['parent_model']));
            }
       } else {
            $this->data['Comment'] = array();
            $this->data['Comment']['parent_model'] = Inflector::pluralize($model);
            $this->data['Comment']['parent_id'] = $parent;
       }
       $this->set('data', $this->data);
       $this->set('parent_model', $this->data['Comment']['parent_model']);
       $this->set('parent_id', $this->data['Comment']['parent_id']);
    }
    
    
    function latest($limit=8) {
        return $this->Comment->find('all', array('conditions'=>array('or'=>array(array('Comment.status'=>ACTIVE), 
                                                                           array('Comment.status'=>WARNING),
                                                                     'Comment.parent_model'=>'Links')),
                                               'order'=>'Comment.created DESC',
                                               'limit'=>$limit,
                                               'recursive'=>-1));
    }
    
    
}

?>
