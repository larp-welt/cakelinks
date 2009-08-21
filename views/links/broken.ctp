<?php $niceHead->css('links')?>
<?php $niceHead->css('forms')?>
<h1>Link melden</h1>
<div id="links">
<div class="links-info">
<p>Der Link geht ins Leere, nur Fehlerseiten? Oder es ist keine LARP-Seite? 
Oder die Seite ist rechtlich bedenklich?</p>

<p>Melde uns bitte das Problem, damit wir uns darum kümmern können.</p>

<p><strong>Hervorgehobenen</strong> Felder sind Pflichtangaben, und 
müssen von Dir ausgefüllt werden.</p>
</div>

<?php echo $widgets->create('Link', array('id'=>'linkform', 'url'=>'/links/broken/'.$data['Link']['id']));
echo $widgets->input('id');
?>

<fieldset><legend><strong>Link</strong></legend>
<?php echo $widgets->input('title', array('label'=>'Titel', 'disabled'=>true));    
      echo $widgets->input('url', array('label'=>'URL', 'disabled'=>true));
      echo $widgets->input('error', array('label'=>'Fehler',
                                          'options'=>array(
                                            '404'=>'Seite nicht gefunden',
                                            'noLarp'=>'Keine LARP-Seite',
                                            'law'=>'Rechtlich bedenkliche Seite'),
                                          'empty'=>'Bitte wählen...',
                                          'div'=>'input select required'));
?>
</fieldset>

<fieldset><legend>Nachricht</legend>
<?php echo $widgets->input('name', array('label'=>'Name'));
      echo $widgets->input('email', array('label'=>'E-Mail'));
      echo $widgets->editor('message', array('label'=>false, 
                                                 'set'=>'bbcode', 
                                                 'skin'=>'simple',
                                                 'parser' => '/links/preview/bbcode' )); ?>
</fieldset>

<?php echo $this->element('human'); ?>

<?php echo $widgets->end('Link melden')?>

</div>