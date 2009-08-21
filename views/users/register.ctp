<?php $niceHead->css('users')?>
<?php $niceHead->css('forms')?>

<h1>Benutzerregistrierung</h1>

<div id="users">
    <div class="users-info">
    <p>Um alle Funktionen von <?php echo Configure::read('Site.Title')?> zu nutzen, musst Du 
    Dich registrieren. Nur dann kannst Du zum Beispiel:</p>
    <ul>
        <li>Links eintragen,</li>
        <li>Kommentare schreiben oder</li>
        <li>Links taggen.</li>
    </ul>
    <p>Alle Felder des Formulars müssen ausgefüllt werden. Weitere freiwillige Angaben kannst Du später in Deinem
    Profil hinterlegen.</p>
    </div>

<?php echo $widgets->create(array('id'=>'userform', 'action'=>'register'));?>
<fieldset><legend>Anmeldedaten</legend>
<?php echo $widgets->input('username', array('label'=>'Anmeldenamen'));
      echo $widgets->input('email', array('label'=>'E-Mail'));
      echo $widgets->input('email2', array('label'=>'E-Mail wiederholen', 
                                        'div'=>'input text required')); ?>
</fieldset>

<fieldset><legend>Passwort</legend>
<?php echo $widgets->passwords('secret', array('label'=>'Passwort',
                                               'type'=>'password'));?>
</fieldset>

<fieldset><legend>Nutzungsbedingungen</legend>
<p><?php 
    $agblabel = ' Ich habe die '.$html->link('Nutzungsbedingungen', '/pages/agb');
    $agblabel .= ' gelesen, und bin mit ihnen einverstanden.';
    echo $widgets->input('agb', array('type'=>'checkbox', 'class'=>'checkbox', 'label'=>$agblabel))?></p>
</fieldset>

<?php echo $widgets->end('Konto anlegen')?>

</div>