<?php

    
class LuceneBehavior extends ModelBehavior {
    
    var $__settings = array(); 
    

    function setup(&$model, $settings=array()) {
        $defaults = array('indexfields'=>array('id'=>'UnIndexed', 'name'=>'UnStored'), 'encoding'=>'utf-8');
        if (!isset($this->__settings[$model->alias])) {
            $this->__settings[$model->alias] = $defaults;
        }
        $this->__settings[$model->alias] = am($this->__settings[$model->alias], ife(is_array($settings), $settings, array()));
    }
    
    
    function afterSave(&$model, $created) {
        // I'm not sure this is a good idea inside Cake, but I had no problems so far
        ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . VENDORS);
        App::import('Vendor', 'Lucene', array('file' => 'Zend'.DS.'Search'.DS.'Lucene.php'));
        App::import('Vendor', 'Numbers', array('file'=>'numbers.php'));
        
        $indexPath = CAKE_CORE_INCLUDE_PATH.DS.APP_DIR.DS.'tmp'.DS.'cache'.DS.'index'; 
        $index = new Zend_Search_Lucene($indexPath); 
        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
        
        $allFields = true;
        foreach ($this->__settings[$model->alias]['indexfields'] as $f=>$t) {
            if (!key_exists($f, $model->data[$model->alias]) && $f != 'id') { $allFields = false; }
        }

        if ($allFields) {

            if (!$created) {
                // delete old record in lucene
                $id = $model->id;
                $term = new Zend_Search_Lucene_Index_Term(spell($id), 'Link_id');
                $docs  = $index->termDocs($term);
                foreach ($docs as $doc) { $index->delete($doc); }
            }

            // add to index
            $doc = new Zend_Search_Lucene_Document();
            $enc = $this->__settings[$model->alias]['encoding'];
            foreach ($this->__settings[$model->alias]['indexfields'] as $f=>$t) {
                if ($f == 'id') { $v = spell($model->id); }
                else { $v = $model->data[$model->alias][$f]; };
                $fname = $model->alias.'_'.$f;
                switch ($t) {
                    case 'UnIndexed':
                      $doc->addField(Zend_Search_Lucene_Field::UnIndexed($fname, $v, $enc));
                      break;
                    case 'UnStored':
                      $doc->addField(Zend_Search_Lucene_Field::UnStored($fname, $v, $enc));
                      break;
                    case 'Keyword':
                      $doc->addField(Zend_Search_Lucene_Field::Keyword($fname, $v, $enc));
                      break;
                }
            }
            $index->addDocument($doc);
        }
    }

    
    function beforeDelete(&$model) {
        // I'm not sure this is a good idea inside Cake, but I had no problems so far
        ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . VENDORS);
        App::import('Vendor', 'Lucene', array('file' => 'Zend'.DS.'Search'.DS.'Lucene.php'));
        App::import('Vendor', 'Numbers', array('file'=>'numbers.php'));
        
        $indexPath = CAKE_CORE_INCLUDE_PATH.DS.APP_DIR.DS.'tmp'.DS.'cache'.DS.'index'; 
        $index = new Zend_Search_Lucene($indexPath); 
        Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');

        $id = $model->id;
        $term = new Zend_Search_Lucene_Index_Term(spell($id), 'Link_id');
        $docs  = $index->termDocs($term);
        foreach ($docs as $doc) { $index->delete($doc); }
    }
    
    
}
    
    
    
    
?>
