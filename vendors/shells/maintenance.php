<?php
/**
 * Import Links from csv-file.
 */
    
class MaintenanceShell extends Shell {
    
    var $uses = array('Hit', 'User', 'Link');
    
    
    function startup() {
        $this->out(str_repeat("-", 72));
        $this->out(Configure::read('Site.Title').'-Maintenance');
        $this->out(str_repeat("-", 72));
        $this->out('App : '. APP_DIR);
        $this->out('Path: '. ROOT . DS . APP_DIR);        
        $this->out(str_repeat("-", 72));
        $this->out('');
    }
    
    function main() { 
        $out = "\t - ClearHits [-days 12]\t defaults to value from config/app.php\n";
        $out .= "\t - ClearApplicants [-days 12]\tdefaults to 14 days\n";
        $out .= "\t - favicons";
        $this->out($out);
    }
    
    
    function ClearHits() {
        $old = (key_exists('days', $this->params)) ? (int) $this->params['days']:Configure::read('Clicks.NoCountPeriod');
        $out = "Deleting hits older then ".$old." days from hits-table.";
        $this->out($out);

        $this->Hit->query('DELETE FROM hits WHERE (TO_DAYS(NOW()) - TO_DAYS(created)) > '.$old); 
    }
    
    
    function favicons() {
        App::import('Vendor', 'favicon', array('file'=>'favicon.php'));
        
        $conditions = array(array('or'=>array(array('Link.status'=>ACTIVE), 
                                              array('Link.status'=>WARNING))));
        $links = $this->Link->find('all', array('conditions'=>$conditions,
                                                'fields'=>array('id', 'url'),
                                                'recursive'=>-1));
        foreach ($links as $link) { 
            
            $icon = new favicon($link['Link']['url'], $link['Link']['id']);
            
            if ($icon->has_icon) {
                $icon->save();
                $this->out('ok');
            } else {
                $this->out('no icon');
            }
            
            unset($icon);
            
        }
    }
    
    
    function ClearApplicants() {
        $old = (key_exists('days', $this->params)) ? (int) $this->params['days']:14;
        $out = "Deleting applicants older then ".$old." days from users-table.";
        $this->out($out);

        $this->Hit->query('DELETE FROM users WHERE group_id = 1 AND (TO_DAYS(NOW()) - TO_DAYS(created)) > '.$old); 
    }
    
}

?>
