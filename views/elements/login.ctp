<?php if (!isset($auth) && $this->name != 'Users') { ?>
<h1 class="login">Login</h1>
<div class="box">
<?php
echo $form->create('User', array('controller'=>'users', 'action'=>'login'));
echo $form->input('username');
echo $form->input('password');
echo $form->end('Login');
?>
<p class="service"><?php echo $html->link('Registrieren', '/users/register'); ?><br />         
<?php echo $html->link('Passwort vergessen?', '/users/reset_pwd'); ?></p>
</div>
<?php } else { 
  if (isset($auth)) {?>
<h1 class="login">Hallo <?php echo $auth['User']['username']?>!</h1>
<div class="box">
<ul>
    <li><?php echo $html->link('Logout', '/users/logout')?></li>
    <li><?php echo $html->link('E-Mail & Passwort', '/users/edit')?></li>
    <li><?php echo $html->link('Profil bearbeiten', '/profiles/edit')?></li>
</ul>
</div>
<?php }
}?>