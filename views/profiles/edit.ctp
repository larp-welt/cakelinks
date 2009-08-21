<?php $niceHead->css('profiles')?>
<?php $niceHead->css('forms')?>

<h1>Ihr Profil</h1>

<div id="profile">

<?php echo $form->create('Profile', array('action' => 'edit', 'type'=>'file'));?>

<fieldset><legend>Kontaktdaten</legend>
<?php echo $form->input('realname', array('label'=>'Realname'));
      echo $form->input('location', array('label'=>'Wohnort'));
      echo $form->input('public_mail', array('label'=>'Öffentliche E-Mail'));
      echo $form->input('homepage', array('label'=>'Homepage'));
?>
</fieldset>

<fieldset><legend>Bilder</legend>
<?php echo $form->input('icon', array('label'=>'Avatar', 'type'=>'file'));
      echo $form->input('image', array('label'=>'Bild', 'type'=>'file'));
?>
</fieldset>

<fieldset><legend>Chat</legend>
<?php echo $form->input('icq', array('label'=>'ICQ'));
      echo $form->input('msn', array('label'=>'MSN'));
      echo $form->input('yahoo', array('label'=>'Yahoo'));
?>
</fieldset>

<fieldset><legend>Beschreibung</legend>
<?php echo $widgets->editor('description', array('label'=>'Beschreibung', 
                                                 'set'=>'bbcode', 
                                                 'skin'=>'simple',
                                                 'parser' => '/links/preview/bbcode' )); ?>
</fieldset>

<?php echo $form->end('Speichern'); ?>

</div>