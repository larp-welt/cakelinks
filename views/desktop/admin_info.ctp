<div id="desktop">
<h1>Allgemeine Information</h1>

<?php
$niceHead->css('forms');
    
echo $form->create(false, array('url'=>'/admin/desktop/info', 
                                'id'=>'infoform'));
?>

<fieldset><legend>Information</legend>
<?php
      echo $widgets->editor('info', array('label'=>false, 
                                          'set'=>'bbcode', 
                                          'skin'=>'simple',
                                          'parser' => '/links/preview/bbcode',
                                          'value'=>$data['info'] ));
?>
</fieldset>

<?php  
echo $form->end('Speichern');
?>
</div>