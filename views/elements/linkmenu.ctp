<?php
  $userid = $allowed = $admin = null;
  if (isset($auth)) $userid = $auth['User']['id'];
  if (isset($acl)) $allowed = $acl->check(array('model' => 'User', 'foreign_key' => $userid), 'links/mod_edit');
  if (isset($acl)) $admin = $acl->check(array('model' => 'User', 'foreign_key' => $userid), 'desktop/admin_index');
?>
<h1 class="link">Links</h1>
<div class="box">
<ul>
<li class="nobullets"><?php echo $html->link('Link anmelden', '/links/add', array('class'=>'add'))?></li>
<?php 
    $modlink = $html->link('Moderation', '/mod/links/index', array('class'=>'mod'));
    $adminlink = $html->link('Administration', '/admin/desktop/index', array('class'=>'mod'));
    if ($allowed) echo '<li class="nobullets">'.$modlink.'</li>';
    if ($admin) echo '<li class="nobullets">'.$adminlink.'</li>'; ?>
</ul>
</div>