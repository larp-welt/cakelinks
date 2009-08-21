<?php
/**
 * User Profiles
 * 
 * Controller for handling of user profiles.
 * 
 * @author $Author: Marcus.Ertl $
 * @version $Rev: 58 $
 */
   
class ProfilesController extends AppController {

    var $uses = array('Link', 'Profile');
    var $helpers = array('Cache', 'Html', 'Form', 'NiceHead', 'Widgets', 'Mailto');
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
    
    /**
     * Edit Profile
     * 
     * Takes no argument, because user can only edit their
     * own profile. User is taken from Auth.
     * 
     * @param none
     */
    function edit() {
        $user = $this->Auth->user();
        
        if (!empty($this->data)) {
            if ($this->Profile->save($this->data)) {
                $this->Session->setFlash('<p>Ihr Profil wurde gespeichert!</p>',
                                         'default', array('class'=>'ok'));
                $this->redirect(array('action'=>'view'));
                exit(0);
            } 
        }

        $this->data = $this->Profile->find('first',
                                           array('conditions'=>
                                                 array('user_id'=>$user['User']['id'])));
        $this->set('data', $this->data);
        $this->set('name', $user['User']['username']);
        $this->pageTitle = 'Profile';
    }
    
    
    function view($id=null) {
        $user = $this->Auth->user();
        if ($id==null && empty($user)) {
            $this->cakeError('error404',array(array('url'=>$slug)));
        } else {
            if ($id==null) $id = $user['User']['id'];
        }

        $profile = $this->Profile->findByUserId($id);
        $this->pageTitle = $profile['User']['username'];
        $links = $this->Link->find('all', array('conditions'=>array('Link.user_id'=>$id,
                                                                    'Link.status'=>ACTIVE)));
        $this->set(compact('profile', 'links'));
    }
    
    
}

?>
