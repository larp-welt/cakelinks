<?php
    
class DesktopController extends AppController {
    
    var $uses = array('Link');
    var $helpers = array('NiceHead', 'Form', 'Widgets');

    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }
    
    function admin_index() {
        
        
    }
    
    function admin_recreateIndex() {
        // Add your vendor directory to the includepath. ZF needs this.
        ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . VENDORS); 

        App::import('Vendor', 'Numbers', array('file'=>'numbers.php'));
        App::import('Vendor', 'Lucene', array('file' => 'Zend'.DS.'Search'.DS.'Lucene.php'));
        
        $indexPath = CAKE_CORE_INCLUDE_PATH.DS.APP_DIR.DS.'tmp'.DS.'cache'.DS.'index'; 
        $index = new Zend_Search_Lucene($indexPath, true); 
        
        $links = $this->Link->find('all');
        
        foreach ($links as $link) {
            $doc = new Zend_Search_Lucene_Document();
            
            $doc->addField(Zend_Search_Lucene_Field::Keyword('Link_id', spell($link['Link']['id']), 'utf-8'));
            $doc->addField(Zend_Search_Lucene_Field::UnStored('Link_title', $link['Link']['title'], 'utf-8'));
            $doc->addField(Zend_Search_Lucene_Field::UnStored('Link_url', $link['Link']['url'], 'utf-8'));
            $doc->addField(Zend_Search_Lucene_Field::UnStored('Link_description', $link['Link']['description'], 'utf-8'));
            
            $tags = array();
            foreach ($link['Tag'] as $tag) { $tags[] = $tag['name']; }
            $tags = implode(', ', $tags);
            $doc->addField(Zend_Search_Lucene_Field::UnStored('Link_tags', $tags, 'utf-8'));
            
            $index->addDocument($doc); 
        }
        
        $index->commit();
        $this->set('return', count($links));
        
    }
    
    
    function admin_info() {
        $file = CAKE_CORE_INCLUDE_PATH.DS.APP_DIR.DS.'tmp'.DS.'cache'.DS.'info'.DS.'info.bbcode';
        if (!empty($this->data)) {
            $fh = fopen($file, 'w') or die('File not found!');
            fwrite($fh, $this->data['info']);
            fclose($fh);
        } else {        
            $this->data['info'] = file_get_contents($file);
        }
        $this->set('data', $this->data);
    }
    
   
}
?>
