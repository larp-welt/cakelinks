<?php
/**
 * Import data
 */

define('ID', 0);
define('NAME', 1);
define('URL', 2);
define('EMAIL', 7);

class ImportShell extends Shell {
    
    var $uses = array('Ringsite', 'Link');
    
    
    function startup() {
        $this->out(str_repeat("-", 72));
        $this->out(Configure::read('Site.Title').' - Import Links');
        $this->out(str_repeat("-", 72));
        $this->out('App : '. APP_DIR);
        $this->out('Path: '. ROOT . DS . APP_DIR);        
        $this->out(str_repeat("-", 72));
        $this->out('');
    }


    function main() { 
        $out = "ring -file file \t imports links from psv file from old larp-welt\n";
        $this->out($out);
    }
    
    
    function ring() {
        if (!key_exists('file', $this->params) || empty($this->params['file'])) {
            $out = "ERROR: No file for import\n";
            $this->out($out);
            exit(1);
        }

        $file = $this->params['file'];
        $out = "Importing '".$file."'";
        $this->out($out);
        
        $fh = fopen($file, 'r') or die('File not found!');
        $links = array();
        while (!feof($fh)) {
            $line = fgets($fh);
            if (!empty($line)) $links[] = explode('|', trim($line));
        }

        foreach ($links[0] as $n=>$v) {
            $this->out("$n\t$v");
        }

        $count = 1;
        foreach ($links as $link) {
            if ($link[19]=='Active') {
                $out = "$count:\t".$link[URL];

                $lw = $this->Link->find(array('Link.url'=>$link[URL]));
                if (empty($lw)) $lw = $this->Link->find(array('Link.url'=>$link[URL].'/'));
                $data = array();
                $data['Ringsite'] = array();
                if (empty($lw)) {
                    $out .= "\nnew link";
                    
                    $data['Ringsite']['id'] = $link[ID];
                    $data['Ringsite']['url'] = $link[URL];
                    $data['Ringsite']['email'] = $link[EMAIL];
                } else {
                    $out .= "\nusing link #".$lw['Link']['id'];
                    
                    $data['Ringsite']['id'] = $link[ID];
                    $data['Ringsite']['link_id'] = $lw['Link']['id'];
                }
                $data['Ringsite']['position'] = $count;
                
                $this->Ringsite->create();
                $this->Ringsite->id = $data['Ringsite']['id'];
                if ($this->Ringsite->save($data, false)) {
                    $out .= "\n".str_repeat("-", 72);
                    $this->out($out);
                    $count++;
                }
            }
        }
        
        fclose($fh);

        
    }
    
    

    
}

?>
