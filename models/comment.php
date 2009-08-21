<?php

class Comment extends AppModel {

    var $belongsTo = 'User';

    var $validate = array(
          'title' => array(
             'rule' => array('minLength', 3),
             'required' => true,
             'message' => 'Bitte gib einen längeren Titel an.'
              ),
          'comment' => array(
              'rule' => array('minLength', 3),
              'required' => true,
              'message' => 'Die Beschreibung ist zu kurz.')
          );

}

?>