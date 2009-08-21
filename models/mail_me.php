<?php

class MailMe extends AppModel {
	var $name = 'MailMe';

        var $useTable = false;

        var $validate = array(
           'name' => array(
             'longEnough' => array(
                 'rule' => array('minLength', 3),
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Der Name sollte mind. 3 Zeichen lang sein!')
              ),
           'email' => array(
             'mail' => array(
                 'rule' => array('email', true),
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Bitte gib eine gültige E-Mail-Adresse an!')
              ),
          'description' => array(
              'rule' => array('minLength', 3),
              'required' => true,
              'allowEmpty' => false,
              'message' => 'Der Text ist zu kurz.'
              ),
          'human' => array(
                 'rule' => array('comparison', '==', 1),
                 'required' => true,
                 'allowEmpty' => false,
                 'message' => 'Bitte bestätige uns, dass Du kein Spambot, sondern ein Mensch bist!',
              ),
           );


}

?>