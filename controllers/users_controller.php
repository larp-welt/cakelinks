<?php

class UsersController extends AppController {

    var $uses = array('User', 'Profile');
    var $helpers = array('Html', 'Form', 'Javascript', 'Widgets', 'NiceHead', 'Paginator');
    var $components = array('SendMail', 'RequestHandler');


    function beforeFilter () {
        parent::beforeFilter();
        $this->Auth->allow('register', 'confirm', 'logout', 'count', 'reset_pwd', 'index', 'tmpCreateProfiles');
        $this->paginate = array('limit'=>Configure::read('Site.ItemsPerPage'));
    }


    function register() {
        if (!empty($this->data)) {
            $this->User->create();
            $this->data['User']['password'] = $this->Auth->password($this->data['User']['secret']);
            $this->data['User']['group_id'] = Configure::read('Member.Group.New');
            $this->data['User']['token'] = $this->_token($this->data['User']['username']);
            if ($this->User->save($this->data)) {
                // create and save profile
                $this->data['Profile']['user_id'] = $this->User->id;
                $this->User->Profile->save($this->data);

                // send mail
                $this->set('token', $this->data['User']['token']);
                $this->set('username', $this->data['User']['username']);

                $this->SendMail->send(array('to'=>$this->data['User']['email'],
                                            'layout'=>'register',
                                            'subject'=>'Ihre Anmeldung bei '.Configure::read('Site.Title')));

                $this->redirect('/pages/registered');
                exit(0);
            }
            unset($this->data['User']['secret']);
            unset($this->data['User']['secret2']);
        }
        $this->pageTitle = 'Anmelden';
    }


    function confirm($token=null) {
        if (!$token) {
            // Errorpage
            $this->set('result', 'no_token');
        } else {
            if ($this->User->hasAny(array('User.token'=>$token))) {
            	$user = $this->User->find('first', array('recursive'=>-1, 'conditions'=>array('User.token'=>$token)));
                $user['User']['group_id'] = 2;
                $user['User']['token'] = null;
                if ($this->User->save($user, false)) {
                    $this->set('result', 'user_active');
                } else {
                    $this->set('result', 'error');
                }
            } else {
                $this->set('result', 'unknown_token');
            }
        }
        $this->pageTitle = 'Bestätigen';
    }


    function login() {
        // cake does the magic
    }


    function logout() {
        $this->Session->del('cachedacl');
        $this->Session->setFlash('Sie haben sich abgemeldet!');
        $this->redirect($this->Auth->logout());
    }


    function edit() {
        $auth = $this->Auth->user();
        $this->User->id = $auth['User']['id'];

        if (empty($this->data)) {
            $this->data = $this->User->read();
        } else {
            if ($this->data['User']['secret'] != '')
                $this->data['User']['password'] = $this->Auth->password($this->data['User']['secret']);
            if ($this->User->save($this->data)) {
                $this->Session->setFlash('Deine Daten wurden gesichert! ', 'default', array('action'=>'edit', 'class'=>'ok'));
                $this->redirect('/');
                exit(0);
            }
        }

        $this->pageTitle = 'E-Mail & Passwort';
        $this->data['User']['password'] = null;
        $this->data['User']['secret'] = $this->data['User']['secret2'] = null;
        $this->set('data', $this->data);
    }


    function reset_pwd($token=null) {
        $error = null;
        if ($token==null) {
            if (!empty($this->data)) {
                if (empty($this->data['User']['account'])) {
                    $this->User->invalidate('account');
                    $error = 'Bitte gib Deinen Benutzername oder Deine E-Mail an!';
                } else {
                    $options = array('conditions'=>array('or'=>array('User.username'=>$this->data['User']['account'],
                                                                     'User.email'=>$this->data['User']['account']),
                                                         'disabled'=>0),
                                     'recursive'=>-1);
                    $user = $this->User->find('first', $options);

                    if (empty($user)) {
                        $this->User->invalidate('account');
                        $error = 'Wir kennen Dich leider weder unter dem Namen noch der E-Mail-Adresse!';
                    } else {
                        $user['User']['token'] = $this->_token($user['User']['username']);
                        $this->User->id = $user['User']['id'];
                        if ($this->User->saveField('token', $user['User']['token'], false)) {
                            // send mail
                            $this->set('token', $user['User']['token']);
                            $this->set('username', $user['User']['username']);

                            $this->SendMail->send(array('to'=>$user['User']['email'],
                                                        'layout'=>'reset_pwd',
                                                        'subject'=>'Ihre Passwort bei '.Configure::read('Site.Title')));

                            $this->redirect('/pages/reset_pwd');
                            exit(0);
                        }

                    }
                }

            }
        } else {
            $user = $this->User->findByToken($token);
            if ($user != false) {
                if (!empty($this->data)) {
                    $this->User->id = $user['User']['id'];
                    $this->User->read();
                    $this->User->data['User']['password'] = $this->Auth->password($this->data['User']['secret']);
                    $this->User->data['User']['token']=null;
                    if ($this->User->save($this->data)) {
                        $this->Session->setFlash('<p>Ihr Passwort wurde geändert!</p><p>Melden Sie sich jetzt bitte an!</p>', 'default', array('action'=>'reset_pwd', 'class'=>'ok'));
                        $this->redirect('/');
                    }
                    $this->data['User']['secret'] = $this->data['User']['secret2'] = null;
                }
            } else {
                $error = "Die Anforderung für die Passwortänderung ist leider ungültig!<br />Forder bitte ein neues Passwort an.";
            }
        }
        $this->pageTitle = 'Neues Passwort';
        $this->set('error', $error);
        $this->set('data', $this->data);
        $this->set('token', $token);
    }


    function index() {
        // public user index

    }


    function view() {
        // public view

    }


    function admin_index() {
        $fields = array('User.id', 'User.username', 'User.email', 'User.disabled', 'Group.name');

        $users = $this->paginate('User'); // , array('fields'=>$fields
        $total = count($users);

        $this->pageTitle = 'User';
        $this->set(compact('total', 'users'));
    }


    function admin_edit($id) {
        // Admin may edit all users and all fields.

        if (!empty($id)) {
            $this->User->bindModel(array('belongsTo'=>array('Group')));
            $this->User->id = $id;

            if (empty($this->data)) {
                $this->data = $this->User->read();
            } else {
                if ($this->data['User']['secret'] != '')
                    $this->data['User']['password'] = $this->Auth->password($this->data['User']['secret']);
                if ($this->User->save($this->data)) {
                    $this->Session->setFlash('Die Mitgliedsdaten wurden gesichert!');
                    $this->redirect('/admin/users/index');
                    exit(0);
                }
                $this->data['User']['id'] = $id;
            }
        } else {
            $this->cakeError('error404',array(array('id'=>$id)));
            exit(0);
        }
        $this->pageTitle = 'Edit User';
        $this->set('groups', $this->User->Group->find('list'));
        $this->data['User']['password'] = null;
        $this->data['User']['secret'] = $this->data['User']['secret2'] = null;
        $this->set('data', $this->data);
    }


    function count() {
        return $this->User->find('count', array('conditions'=>array('User.disabled'=>0), 'recursive'=>-1));
    }



    function _token($string='') {
        if ($string=='') $string = sqrt(rand(9999));
        return md5(microtime() . rand(1000,9999) . $string);
    }


//    function tmpCreateProfiles() {
//        $profiles = $this->Profile->find('all', array('fields'=>array('user_id')));
//        $ids = array();
//        foreach ($profiles as $p) $ids[] = $p['Profile']['user_id'];
//
//        $users = $this->User->find('all', array('conditions'=>array(array('NOT'=>array('User.id'=>$ids)))));
//
//        foreach ($users as $user) {
//            $this->Profile->create();
//            $this->data['Profile']['user_id'] = $user['User']['id'];
//            $this->Profile->save($this->data);
//        }
//    }

}

?>