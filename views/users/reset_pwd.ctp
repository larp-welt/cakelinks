<?php $niceHead->css('users')?>
<?php $niceHead->css('forms')?>

<h1>Passwort vergessen</h1>
<div id="users">
<?php if (empty($token)) { ?>
    <div class="login-info">
    <p>Passwort vergessen? Kein Problem! Gib einfach Deinen Usernamen oder Deine E-Mailadresse an. Wir
    schicken Dir dann eine E-Mail, die Dir erklärt, wie Du das Passwort ändern kannst.</p>
    <p>Solltest du noch keinen Account bei <?php echo Configure::read('Site.Title')?> haben, 
    dann <?php echo $html->link('registriere', array('controller'=>'users', 'action'=>'register'))?> Dich am besten gleich.</p>
    </div>
<?php echo $form->create('User', array('action' => 'reset_pwd'));?>
    <fieldset><legend>Login oder E-Mail</legend>
<?php echo $form->input('account', array('error'=>false)); 
      echo $form->error('User/account', $error); ?>
    </fieldset>
<?php echo $form->end('Neues Passwort');?>
<?php } else { 
    if ($error==null) { ?>
    <div class="login-info">
    <p>Bitte gib ein neues Passwort an, mit diesem kannst Du Dich dann bei LARP-Welt anmelden.</p>
    </div>
<?php echo $form->create('User', array('action' => 'reset_pwd/'.$token));?>
<fieldset><legend>Passwort</legend>
    <?php echo $widgets->passwords('secret', array('label'=>'Passwort',
                                                   'type'=>'password'));?>
</fieldset>
<?php echo $form->end('Neues Passwort');
    } else { ?>
    <div class="login-info">
    <p><?php echo $error ?></p>
    </div>
<?php }
} ?>
</div>