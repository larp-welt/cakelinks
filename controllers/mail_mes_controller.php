<?php

class MailMesController extends AppController {
    var $name = 'MailMes';
    
    var $helpers = array('Html', 'Form', 'Javascript', 'Widgets', 'Cache', 'NiceHead', 'Paginator');
    var $components = array('RequestHandler', 'SendMail');
    
    
    function beforeFilter () {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }
    
    
    function send() {
        if ($this->RequestHandler->isPost()) {
            $this->MailMe->set($this->data);
            if ($this->MailMe->validates()) {
                $this->set('data', $this->data);
                $this->SendMail->send(array('from'=>array($this->data['MailMe']['email']=>$this->data['MailMe']['name']),
                                            'layout'=>'mail_me',
                                            'subject'=>Configure::read('Site.Title').'-Kontaktformular'));

                $this->Session->setFlash('<p>Die E-Mail wurde an uns gesandt!</p>',
                                         'default', array('action'=>'index', 'class'=>'ok'));
                $this->redirect('/');
                exit(0);
            }
        }
    }

    
}
    
?>
