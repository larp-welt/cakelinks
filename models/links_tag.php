<?php


class LinksTag extends AppModel {
	var $name = 'LinksTag';


	function changeTag($old, $new) {
       $this->query("UPDATE links_tags SET tag_id=".$new." WHERE tag_id=".$old);

       // deduplizieren
       $rows = $this->find('all', array('conditions'=>array('tag_id'=>$new),
                                        'group'=>'link_id',
    									'fields'=>array('link_id', 'count(*) AS cnt')));
       foreach ($rows as $row) {
	   		if ($row[0]['cnt'] > 1) {
				$limit = $row[0]['cnt']-1;
				$sql = 'DELETE FROM links_tags WHERE link_id='.$row['LinksTag']['link_id'].' AND tag_id='.$new;
				$sql = $sql.' LIMIT '.$limit;
				$this->query($sql);
			}
        }
		return true;
	}

}

?>