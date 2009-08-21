<?php $niceHead->css('users')?>
<?php $niceHead->css('forms')?>
<?php if  ($session->check('Message.auth')) $session->flash('auth');?>

<h1>Login</h1>
<div id="users">
    <div class="login-info">
    <p>Um alle Funktionen von <?php echo Configure::read('Site.Title')?> zu nutzen, musst Du 
    Dich anmelden. Nur dann kannst Du zum Beispiel:</p>
    <ul>
        <li>Links eintragen,</li>
        <li>Kommentare schreiben oder</li>
        <li>Links taggen.</li>
    </ul>
    <p>Solltest du noch keinen Account bei <?php echo Configure::read('Site.Title')?> haben, 
    dann <?php echo $html->link('registriere', array('controller'=>'users', 'action'=>'register'))?> Dich am besten gleich.</p>
    </div>
<?php echo $form->create('User', array('action' => 'login'));?>
    <fieldset><legend>Login</legend>
<?php echo $form->input('username');
      echo $form->input('password', array('div'=>'input required'));?>
    </fieldset>
<?php echo $form->end('Login');?>
</div>