<?php

class AppController extends Controller {
    
    var $components = array('Acl', 'Auth', 'Tracks');
    var $helpers = array('Smartypants', 'Form', 'Html', 'Tracks');
    var $publicControllers = array('pages');

    
    function beforeFilter() {
        if (isset($this->Auth)) {
          $this->Auth->userScope = array('User.disabled' => 0);
          $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
          $this->Auth->logoutRedirect = array('controller' => 'tags', 'action' => 'index');
          $this->Auth->loginRedirect = array('controller' => 'tags', 'action' => 'index');
          $this->Auth->authError = '<p>Um die Seite aufzurufen musst Du angemeldet sein!</p>';
          $this->Auth->loginError = '<p>Anmeldung fehlgeschlagen!</p><p>Der Username oder das Passwort war ungültig!</p>';
          $this->Auth->authorize = 'actions';

          if (in_array(low($this->params['controller']), $this->publicControllers)) {
                $this->Auth->allow();
          }
          $this->set('auth',$this->Auth->user());
          if ($this->Auth->user()) $this->set('acl',$this->Acl);
        }
    }
    
}
    
?>
