<?php echo $widgets->create(array('url'=>'/mod/links/edit/'.$data['Link']['id'], 'id'=>'linkform'));
echo $widgets->input('id');
?>



<fieldset><legend>Allgemeine Daten</legend>
<?php echo $widgets->input('title', array('label'=>'Titel'));
      echo $widgets->input('url', array('label'=>'Adresse'));
      echo $widgets->editor('description', array('label'=>'Beschreibung', 
                                                 'set'=>'bbcode', 
                                                 'skin'=>'simple',
                                                 'parser' => '/links/preview/bbcode' )); ?>
</fieldset>

<fieldset><legend><strong>Tags</strong></legend>
    <?php echo $form->error('Tag.Tag', 'Bitte wählen Sie mindestens einen Tag.');?>
    <?php echo $widgets->TagChooser('Tag.Tag')?>
</fieldset>

<fieldset><legend class="down toggle">Kalenderdaten</legend>
<?php echo $widgets->DatePicker('start');
      echo $widgets->DatePicker('end'); ?>
</fieldset>

<fieldset><legend class="down toggle">Geographische Angaben</legend>
<?php echo $widgets->input('lng', array('label'=>'Breitengrad'));
      echo $widgets->input('alt', array('label'=>'Längengrad')); ?>
</fieldset>

<fieldset><legend class="moderation">Moderation</legend>
<?php echo $widgets->input('status', array('label'=>'Status',
                                     'options'=>array(
                                         INACTIVE=>'Inaktiv',
                                         ACTIVE=>'Aktiv',
                                         WARNING=>'Warnung',
                                         BROKEN=>'Ungültig',
                                         FRESH=>'Neu'))) ?>    
</fieldset>

<?php echo $widgets->end('Link speichern')?>

