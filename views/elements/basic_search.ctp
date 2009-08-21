<div id="searchbox">
<?php
    if (!isset($q)) $q='';
    echo $form->create('Links', array('controller'=>'links', 'action'=>'search', 'type'=>'get'));
    echo $form->input('q', array('label'=>false, 'value'=>$q));
    echo $form->end('Suchen'); 
    if (!empty($q)) $this->passedArgs['?'] = 'q='.$q;
?>
</div>