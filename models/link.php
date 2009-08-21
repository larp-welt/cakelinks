<?php

class Link extends AppModel {

    var $hasAndBelongsToMany = array('Tag'=>array('unique'=>true));
    var $hasOne = 'Ringsite';
    var $belongsTo = 'User';
    var $hasMany = array('Hit',
                         'Comment'=>array('conditions'=>"Comment.parent_model = 'Link'",
                                          'order' => 'Comment.created DESC',
                                          'foreignKey'=>'parent_id'));

    var $actsAs = array('Lucene'=>array('indexfields'=>array('id'=>'Keyword',
                                                             'title'=>'UnStored',
                                                             'url'=>'UnStored',
                                                             'description'=>'UnStored',
                                                             'tags'=>'UnStored'),
                                        'encoding'=>'utf-8'));

    var $validate = array(
          'title' => array(
             'rule' => array('minLength', 3),
             'required' => true,
             'message' => 'Bitte gib einen längeren Titel an.'
              ),
          'url' => array(
              'isurl'=>array(
                  'rule' => 'url',
                  'required' => true,
                  'message' => 'Die URL ist ungültig.'
                  ),
              'unique'=>array(
                  'rule' => 'isUnique',
                  'message' => 'Der Link ist bereits in der Datenbank.'
                  )
              ),
          'description' => array(
              'rule' => array('minLength', 3),
              'required' => true,
              'message' => 'Die Beschreibung ist zu kurz.'
          ),
          'lng' => array(
              'rule' => 'decimal',
              'allowEmpty' => true,
              'required' => false,
              'message' => 'Der Längengrad ist ungültig.'
          ),
          'alt' => array(
              'rule' => 'decimal',
              'allowEmpty' => true,
              'required' => false,
              'message' => 'Der Breitengrad ist ungültig.'
          ),
          'start' => array(
              'rule' => array('date', 'dmy'),
              'allowEmpty' => true,
              'required' => false,
              'message' => 'Bitte ein gültiges Datum angeben.'
          ),
          'end' => array(
              'rule' => array('date', 'dmy'),
              'allowEmpty' => true,
              'required' => false,
              'message' => 'Bitte ein gültiges Datum angeben.'
          ),
          'Tag' => array(
              'rule' => array('comparison', '>=', 1),
              'allowEmpty' => false,
              'required' => true
          )
        );

    var $validateBroken = array(
        'error' => array(
             'rule' => array('minLength', 1),
             'required' => true,
             'allowEmpty' => false,
             'message' => 'Bitte gib an, warum Du uns den Link meldest.'
             ),
        'human' => array(
             'rule' => array('comparison', '==', 1),
             'required' => true,
             'allowEmpty' => false,
             'message' => 'Bitte bestätige uns, dass Du kein Spambot, sondern ein Mensch bist!',
             )
        );

    var $validateModIndex = array();


    function beforeValidate() {
        // XXX: https://trac.cakephp.org/ticket/3604
        if (key_exists('Tag', $this->data) && is_array($this->data['Tag']['Tag'])) {
            $this->data['Link']['Tag'] = count($this->data['Tag']['Tag']);
        }

        // we need this field for lucene
        if (key_exists('Tag', $this->data) && !empty($this->data['Tag']['Tag'])) {
            $temptags = $names = array();
            foreach ($this->data['Tag']['Tag'] as $k=>$id)  $temptags[] = $id;
            $temptags = $this->Tag->find('all', array('conditions'=>array('id'=>$temptags),
                                                            'fields'=>array('name'),
                                                            'recursive'=>-1));
            foreach ($temptags as $tg) $names[] = $tg['Tag']['name'];
            $this->data['Link']['tags'] = implode(', ', $names);
        }
    }


    function beforeSave() {
        App::import('Vendor', 'dateformater', array('file'=>'dateformater.php'));

        if (isset($this->data['Link']['title'])) $this->data['Link']['slug'] = $this->stringToSlug($this->data['Link']['title']);

        if (key_exists('start',  $this->data['Link']))
            $this->data['Link']['start'] = format_date($this->data['Link']['start'], 'mysql-date');
        if (key_exists('end',  $this->data['Link']))
            $this->data['Link']['end'] = format_date($this->data['Link']['end'], 'mysql-date');

        if (empty($this->data['Link']['lng'])) $this->data['Link']['lng'] = null;
        if (empty($this->data['Link']['alt'])) $this->data['Link']['alt'] = null;

        if (isset($this->data['Link']['url'])) {
            // if no protokoll is give, we asume it is http
            $tmp = strstr($this->data['Link']['url'], '//');
            if (empty($tmp)) {
                $this->data['Link']['url'] = 'http://'.$this->data['Link']['url'];
            }
        }
        return true;
    }


    function afterFind($results) {

       require_once 'Date.php'; // PEAR

       $today = new Date();

       foreach ($results as $key => $val) {
           if (isset($val['Link']['start'])) {
               $results[$key]['Link']['start'] = date('d.m.Y',strtotime($val['Link']['start']));
               if ($results[$key]['Link']['start'] == '01.01.1970') $results[$key]['Link']['start'] = null;
           }
           if (isset($val['Link']['end'])) {
               $results[$key]['Link']['end'] = date('d.m.Y',strtotime($val['Link']['end']));
               if ($results[$key]['Link']['end'] == '01.01.1970') $results[$key]['Link']['end'] = null;
           }

           if (isset($val['Link']['created']) && isset($val['Link']['hit_count'])) {
	           $created = new Date($results[$key]['Link']['created']);
	           $span = new Date_Span();
	           $span->setFromDateDiff($today, $created);
	           $old = $span->toDays();

		       if ($old >= 1) {
		       		$results[$key]['Link']['hits_per_day'] = $results[$key]['Link']['hit_count']/$old;
		       } else {
		       		$results[$key]['Link']['hits_per_day'] = $results[$key]['Link']['hit_count'];
		       }

		       $results[$key]['Link']['age'] = $old;

		       unset($created, $span);
           }
       }

       return $results;
    }


    function paginate($conditions = null, $fields = null, $order = null, $limit = null, $page = 1, $recursive = 1) {
        if (is_array($conditions) && array_key_exists('LinksTag.tag_id', $conditions))  {
            $this->bindModel(array('hasOne'=>array('LinksTag'=>array())));
        }
        return $this->find('all',
                      array('conditions'=>$conditions,
                            'fields'=>$fields,
                            'order'=>$order,
                            'limit'=>$limit,
                            'page'=>$page,
                            'recursive'=>$recursive));
    }


    function paginateCount($conditions = null, $recursive = 1) {
        if (is_array($conditions) && array_key_exists('LinksTag.tag_id', $conditions))  {
            $this->bindModel(array('hasOne'=>array('LinksTag'=>array())));
        }
        return $this->find('count', array('conditions'=>$conditions,
                                          'recursive'=>$recursive));
    }


    function parentNode() {
        return null;
    }


    function setStatus($status) {
        return $this->saveField('status', $status, false);
    }


    function countHit() {
        /*
         * I'm don't using counterCache for counting hits.
         * I want to be able to delete old hits from the hits
         * table.
         */
        App::import('Vendor', 'ip');

        $ip = _getip();
        $client = ip2long($ip);

        $sql = "SELECT count(*) FROM hits WHERE ";
        $sql .= "ip='".$client."' AND link_id='".$this->id."' ";
        // mySQL >=5: $sql .= "AND DATEDIFF(NOW(), created) < 1";
        $sql .= "AND (TO_DAYS(NOW()) - TO_DAYS(created)) < ".Configure::read('Clicks.NoCountPeriod');
        $allreadyClicked = $this->query($sql);

        if ($allreadyClicked[0][0]['count(*)'] == 0) {
            $this->Hit->create();
            $this->Hit->data['Hit']['link_id'] = $this->id;
            $this->Hit->data['Hit']['ip'] = $client;
            $this->Hit->save($this->Hit->data);

            $this->saveField('hit_count', $this->data['Link']['hit_count']+1, false);
        }
    }


}

?>