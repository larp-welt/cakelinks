<?php

//
// $Rev:: 76                    $:  Revision of last commit
// $Author:: Marcus.Ertl        $:  Author of last commit
// $Date:: 2008-12-13 15:13:38 #$:  Date of last commit
//

class TagsController extends AppController {

	var $uses = array('Tag', 'LinksTag', 'Link');
    var $helpers = array('Html', 'Form', 'Javascript', 'Widgets', 'Cache', 'NiceHead', 'Paginator');
    var $cacheAction = false;


    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'tagCountsActive');
        $this->paginate = array('limit'=>2*Configure::read('Site.ItemsPerPage'), 'order'=>array('name'=>'ASC'));
    }


    function index() {
        $this->pageTitle = 'Themen';
    }

    function tagCountsActive() {
        return $this->Tag->tagCountsActive();
    }


    function mod_index() {
        return $this->set('tags', $this->paginate('Tag'));
    }

    function mod_edit($id=null) {
        if (!$id && empty($this->data)) {
                $this->cakeError('error404',array(array('action'=>'mod_edit')));
        }

        if ($id == 0) {
        	$this->cakeError('error500',array(array('action'=>'mod_edit')));
        }

        if (!empty($this->data)) {
                if ($this->Tag->save($this->data)) {
                        $this->Session->setFlash('<p>Der Tag wurde gespeichert!</p>',
                                                 'default', array('class'=>'ok'));
                        $this->redirect('/mod/tags/index');
                        exit(0);
                } else {
                }
        } else  {
                $this->data = $this->Tag->read(null, $id);
        }
        $this->pageTitle = 'Tag moderieren';
        $this->set('data', $this->data);
    }

    function mod_delete($id=null) {
        if (!$id && empty($this->data)) {
                $this->cakeError('error404',array(array('action'=>'mod_delete')));
        }

        if ($id == 0) {
        	$this->cakeError('error500',array(array('action'=>'mod_edit')));
        }

        if (!empty($this->data)) {
        	$new = $this->data['Tag']['new'];

        	$this->LinksTag->changeTag($id, $new);
        	$this->Tag->query('DELETE FROM tags WHERE id='.$id);

        	// cache der tagcloud löschen
        	unlink(CAKE_CORE_INCLUDE_PATH.DS.APP_DIR.DS.'tmp'.DS.'cache'.DS.'views/element_cache_tagcloud');

            $this->Session->setFlash('<p>Der Tag wurde gelöscht!</p>',
                                     'default', array('class'=>'ok'));
            $this->redirect('/mod/tags/index');
            exit(0);
        } else {
        	$this->Tag->unbindModel(array('hasAndBelongsToMany'=>array('Link')));
        	$this->data = $this->Tag->read(null, $id);
        }

    	$tags = $this->Tag->find('list');
    	unset($tags[$id]);
    	asort($tags);

    	$this->pageTitle = 'Tag löschen';
		$this->set('tags', $tags);
		$this->set('data', $this->data);
    }


    function mod_view($slug=null) {
    	if (!$slug) {
    		 $this->cakeError('error404',array(array('action'=>'mod_view')));
    	}

    	$data = $this->Tag->find('all', array('conditions'=>array('slug'=>$slug)));

    	$this->set('data', $data[0]);
    }
}

?>
