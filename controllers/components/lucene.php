<?php 
/**
 * Fulltext index using Zend's Lucene
 * 
 * Component for querying a fulltext index.
 * 
 * Use the lucene-Behavior in the correspondending model for creating and
 * updating of the index.
 * 
 * @author $Author: Marcus.Ertl $
 * @version $Rev: 58 $
 */
// I'm not sure this is a good idea inside Cake, but I had no problems so far
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . VENDORS);
App::import('Vendor', 'Lucene', array('file' => 'Zend'.DS.'Search'.DS.'Lucene.php'));

class LuceneComponent extends Object {
	var $controller = true;
	var $index = null;
	
	function startup(&$controller) {
	}	

	// Get the index object
	function &getIndex() {
                Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
                
		if(!$this->index) {
                        $indexPath = CAKE_CORE_INCLUDE_PATH.DS.APP_DIR.DS.'tmp'.DS.'cache'.DS.'index'; 
			$this->index = new Zend_Search_Lucene($indexPath);
		}
		return $this->index;
	}
	
        /**
         * Queries the fulltext index
         * 
         * All single words will be prefixed and postfixed with an asterix (*)
         * for substring matches.
         * 
         * @param string $query For example: 'Lucene -mysql'
         * @return array Returns array of id's of matching rows.
         * @todo querying multiple indeces
         */
	function query($query) {
            
                function addstars($w) {
                    if (!preg_match('/[\)*~"]$/', $w)) $w .= '*';
                    if (!preg_match('/^["\(*+-]/', $w)) $w = '*'.$w;
                    return $w;
                }
                
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
                
                $q = preg_replace('/\s+/', ' ', $query);
                $q = explode(' ', $q);
                $q = array_map("addstars", $q);
                $query = implode(' ', $q);

		$index =& $this->getIndex();
		$results = $index->find($query);
		return $results;
	}
}
?>