<?php $niceHead->css('forms')?>
<?php $niceHead->css('mailme')?>

<h1>Kontakt</h1>

<div id="mailme">
    <div class="mail-info">
    <p>Hier hast Du die Möglichkeit uns eine Nachricht zu schicken.</p>
    
    <p>Bitte gib eine gültige E-Mailadresse an, damit wir Dich im Falle von 
    Rückfragen erreichen können.</p>

    <p>Alle Felder des Formulars müssen ausgefüllt werden.</p>
    </div>

<?php echo $widgets->create('MailMe', array('id'=>'mailform', 'action'=>'send'));?>
<fieldset><legend>Ihre Nachricht an uns</legend>
<?php echo $widgets->input('name', array('label'=>'Namen'));
      echo $widgets->input('email', array('label'=>'E-Mail'));
      echo $widgets->editor('description', array('label'=>false, 
                                             'set'=>'bbcode', 
                                             'skin'=>'simple',
                                             'parser' => '/links/preview/bbcode' ));  ?>
</fieldset>

<?php echo $this->element('human'); ?>

<?php echo $widgets->end('Nachricht senden')?>

</div>