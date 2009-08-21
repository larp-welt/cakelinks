<?php
/**
 * RSS Data Source
 * 
 * Get's RSS-Feeds and makes them available as data for normal
 * models.
 * 
 * @author $Author: Marcus.Ertl $
 * @version $Rev: 58 $
 */


class RssSource extends DataSource {
    var $rss = null;
    
    function __construct($config) {
        parent::__construct($config);

        App::import('Vendor', 'lastRSS', array('file'=>'lastRSS.php'));
        $cache = CACHE . 'rss' . DS;

        $this->rss = new lastRSS;
        $this->rss->cache_dir = $cache;
        $this->rss->cache_time = $this->config['cacheTime']*60;
        
        if (!file_exists($cache)) {
            uses('folder');
            $folder = new Folder();
            $folder->mkdir($cache);
        }
        

    }


    function close() {
        return true;
    }

    
    function read() {
        if ($rss = $this->rss->get($this->config['url'])) {
            $items = $rss;
            return $items;
        } else {
            return false;
        }        
    }
}


?>
