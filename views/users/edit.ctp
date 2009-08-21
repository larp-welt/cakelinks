<?php $niceHead->css('users')?>
<?php $niceHead->css('forms')?>

<h1>Mitgliedsdaten</h1>

<div id="users">

<?php echo $form->create('User', array('action' => 'edit'));?>

<fieldset><legend>Mitgliedsdaten</legend>
<div class="input"><label>Username</label><div><?php echo $auth['User']['username'] ?></div></div>
<?php echo $form->input('email');?>
</fieldset>

<fieldset><legend>Passwort</legend>
<?php echo $widgets->passwords('secret', array('label'=>'Passwort',
                                               'type'=>'password'));?>
</fieldset>

<?php echo $form->end('Speichern');?>

</div>
