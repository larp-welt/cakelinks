<?php

class User extends AppModel {
	var $name = 'User';
        
        var $hasMany = 'Link';
        var $hasOne = 'Profile';
        var $belongsTo = 'Group';
        var $actsAs = array('Acl' => array('type' => 'requester'));
        
        var $validate = array(
          'username' => array(
             'longEnough' => array(
                 'rule' => array('minLength', 3),
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Bitte gib einen Usernamen von mind. 3 Zeichen Länge an!'),
             'unique' => array(
                 'rule' => 'isUnique',
                 'message' => 'Den Namen gibt es bereits!'),
             'alphanum' => array(
                 'rule' => 'alphaNumeric',
                 'message' => 'Bitte gib einen Usernamen aus Buchstaben und Zahlen an!')
              ),
           'secret' => array(
             'longEnough' => array(
                 'rule' => array('minLength', 6),
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Das Passwort muss mind. 6 Zeichen lang sein!'),
              'eqToPwd2' => array(
                  'rule' => array('identicalFieldValues', 'secret2'),
                  'message' => 'Die Passwörter stimmen nicht überein!')
              ),
           'email' => array(
             'mail' => array(
                 'rule' => array('email', true),
                 'message' => 'Bitte gib eine gültige E-Mail-Adresse an!'),
             'unique' => array(
                 'rule' => 'isUnique',
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Es existiert bereits ein Konto mit dieser Adresse!'),
             'eqToMail2' => array(
                 'rule' => array('identicalFieldValues', 'email2'),
                 'message' => 'Die Adressen stimmen nicht überein!')
              ),
              'agb' => array(
                 'rule' => array('comparison', '==', 1),
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Bitte erkennen Sie die Nutzungsbedingungen an!',
              ),
           );

        var $validateEdit = array(
           'secret' => array(
             'longEnough' => array(
                 'rule' => array('minLength', 6),
                 'required' => false,
                 'allowEmpty' => true,
                 'message' => 'Das Passwort muss mind. 6 Zeichen lang sein!'),
              'eqToPwd2' => array(
                 'rule' => array('identicalFieldValues', 'secret2'),
                 'message' => 'Die Passwörter stimmen nicht überein!')
              ),
           'email' => array(
             'mail' => array(
                 'rule' => array('email', true),
                 'message' => 'Bitte gib eine gültige E-Mail-Adresse an!'),
             'unique' => array(
                 'rule' => 'isUnique',
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Es existiert bereits ein Konto mit dieser Adresse!'),
              )
           );
           
         var $validateResetPwd = array(
           'secret' => array(
             'longEnough' => array(
                 'rule' => array('minLength', 6),
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Das Passwort muss mind. 6 Zeichen lang sein!'),
              'eqToPwd2' => array(
                 'rule' => array('identicalFieldValues', 'secret2'),
                 'message' => 'Die Passwörter stimmen nicht überein!')
              )
           );
           
        var $validateAdminEdit = array(
          'username' => array(
             'longEnough' => array(
                 'rule' => array('minLength', 3),
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Bitte gib einen Usernamen von mind. 3 Zeichen Länge an!'),
             'unique' => array(
                 'rule' => 'isUnique',
                 'message' => 'Den Namen gibt es bereits!'),
             'alphanum' => array(
                 'rule' => 'alphaNumeric',
                 'message' => 'Bitte gib einen Usernamen aus Buchstaben und Zahlen an!')
              ),
           'secret' => array(
             'longEnough' => array(
                 'rule' => array('minLength', 6),
                 'required' => false,
                 'allowEmpty' => true,
                 'message' => 'Das Passwort muss mind. 6 Zeichen lang sein!'),
              'eqToPwd2' => array(
                 'rule' => array('identicalFieldValues', 'secret2'),
                 'message' => 'Die Passwörter stimmen nicht überein!')
              ),
           'email' => array(
             'mail' => array(
                 'rule' => array('email', true),
                 'message' => 'Bitte gib eine gültige E-Mail-Adresse an!')
              )
           );
           

      function beforeSave() {
          $this->data['User']['slug'] = $this->stringToSlug($this->data['User']['username']);

          return true;
      }
        
    
      function identicalFieldValues( $field=array(), $compare_field=null ) {
          // http://bakery.cakephp.org/articles/view/
          // using-equalto-validation-to-compare-two-form-fields

          foreach( $field as $key => $value ){
                $v1 = $value;
                $v2 = $this->data[$this->name][ $compare_field ];                 
                if($v1 !== $v2) {
                    return false;
                } else {
                    continue;
                }
            }
            return true;
      } 
      
      
      function parentNode() {
        if (!$this->id) { return null; }

        $data = $this->read();

        if (!$data['User']['group_id']){
          return null;
        } else {
          return array('model' => 'Group', 'foreign_key' => $data['User']['group_id']);
        }
      }
      
      
      function getGroupmembers($groups) {
          if (!is_array($groups)) $groups = array($groups);
          
          return $this->find('all', array('conditions'=>array('User.group_id'=>$groups,
                                                              'disabled'=>0),
                                            'fields'=>array('email', 'username'),
                                            'recursive'=>-1));
      }

      
      function getGroupmails($groups) {
          $users = $this->getGroupmembers($groups);
          
          $mails = array();
          foreach ($users as $user) $mails[$user['User']['email']] = $user['User']['username'];
          
          return $mails;
      }
      
}
?>