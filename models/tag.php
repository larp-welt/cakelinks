<?php

//
// $Rev:: 89                    $:  Revision of last commit
// $Author:: Marcus.Ertl        $:  Author of last commit
// $Date:: 2009-02-15 23:36:57 #$:  Date of last commit
//

class Tag extends AppModel {
	var $name = 'Tag';

	var $hasAndBelongsToMany = array('Link'=>array('unique'=>true));

        function beforeSave() {
            $this->data['Tag']['slug'] = $this->stringToSlug($this->data['Tag']['name']);

            return true;
        }


	function tagCountsActive() {
		require_once 'Date.php'; // PEAR
        $today = new Date();

		$sql = "SELECT count(*) AS cnt, T.name, T.id, T.slug, max(L.created) AS created FROM links_tags AS LT, tags AS T, links AS L ";
		$sql .= "WHERE LT.tag_id=T.id AND (L.status = ".ACTIVE." OR L.status = ".WARNING.") AND LT.link_id=L.id AND LT.tag_id!=0 GROUP BY LT.tag_id order by T.name";
		$cloud = $this->query($sql);

		$min = $cloud[0][0]['cnt'];
		$max = $min;

		foreach ($cloud as &$tag) {
			if ($min > $tag[0]['cnt']) {$min = $tag[0]['cnt'];}
			if ($max < $tag[0]['cnt']) {$max = $tag[0]['cnt'];}

			$age = new Date($tag[0]['created']); // L <=> 0
	        $span = new Date_Span();
	        $span->setFromDateDiff($today, $age);
	        $tag[0]['age'] = $span->toDays();
		}

		return array($cloud, $min, $max);
	}


	function tagsWithCount($limit=null, $page=null) {
		$sql = "SELECT count(*) AS cnt, Tag.name, Tag.id, Tag.slug FROM links_tags AS LT, tags AS Tag, links AS L ";
		$sql .= "WHERE LT.tag_id=Tag.id AND L.status != ".DELETED." AND LT.link_id=L.id GROUP BY LT.tag_id ORDER BY Tag.name";
        if (!empty($limit)) $sql .= " LIMIT $limit";
        if (!empty($page)) $sql .= " OFFSET ".$limit*($page-1);
		$tags = $this->query($sql);

		return $tags;
	}


	function allTagsWithCount($limit=null, $page=1) {
		$sql = "SELECT count(L.id) AS cnt, T.name, T.id, T.slug ";
		$sql .= "FROM tags T LEFT JOIN (links_tags LT LEFT JOIN links L ON LT.link_id=L.id AND L.status != ".DELETED.") ";
		$sql .= "ON LT.tag_id=T.id ";
		$sql .= "GROUP BY T.id ORDER BY T.name";
        if (!empty($limit)) $sql .= " LIMIT $limit";
        if (!empty($page) && $page>1) $sql .= " OFFSET ".$limit*($page-1);

        return $this->query($sql);
	}


   function paginate($conditions = null, $fields = null, $order = null, $limit = null, $page = 1, $recursive = 1) {
        return $this->allTagsWithCount($limit, $page);
   }


   function paginateCount($conditions = null, $recursive = 1) {
        return count($this->allTagsWithCount());

   }


  function createIfNew($name) {
        if (!$this->hasAny(array('Tag.name'=>$name))) {
                $this->create();
                $this->data['Tag']['name'] = $name;
                $this->save();
            }
        $current = $this->find('first', array('recursive'=>-1, 'conditions'=>array('Tag.name'=>$name)));
        return $current['Tag']['id'];
   }


}

?>