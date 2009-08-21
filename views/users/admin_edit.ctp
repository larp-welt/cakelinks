<div id="users">
<h1>Mitglied bearbeiten: <?php echo $data['User']['username'];  ?></h1>

<?php
$niceHead->css('users');
$niceHead->css('forms');
    
echo $form->create(array('url'=>'/admin/users/edit/'.$data['User']['id'], 'id'=>'userform'));
?>

<fieldset><legend>Basisdaten</legend>
<?php
echo $widgets->input('username', array('label'=>'Username'));
echo $widgets->input('email', array('label'=>'E-Mail'));
?>
</fieldset>

<fieldset><legend>Passwort</legend>
<?php echo $widgets->passwords('secret', array('label'=>'Passwort',
                                               'type'=>'password'));?>
</fieldset>

<fieldset><legend>Verwaltung</legend>
<?php
echo $widgets->input('group_id', array('label'=>'Gruppe'));
echo $widgets->input('disabled', array('label'=>'Deaktiviert', 'type'=>'checkbox'));
?>
</fieldset>

<?php  
echo $form->end('Speichern');
?>
</div>