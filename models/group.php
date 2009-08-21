<?php

class Group extends AppModel {
	var $name = 'Group';

        var $hasMany = 'User';
        var $actsAs = array('Acl' => array('type' => 'requester'), 'Tree');

        var $validate = array(
          'name' => array(
             'longEnough' => array(
                 'rule' => array('minLength', 3),
                 'required' => true,
                 'allowEmpty' => false),
             'unique' => array(
                 'rule' => 'isUnique'),
             'alphanum' => array(
                 'rule' => 'alphaNumeric')
              ),
           );


      function parentNode() {
            if (!$this->id) {
              return null;
            }

            $data = $this->read();

            if (!$data['Group']['parent_id']){
              return null;
            } else {
              return array('model' => $this->name, 'foreign_key' => $data['Group']['parent_id']);
            }
      }

}

?>