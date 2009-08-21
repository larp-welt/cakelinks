<?php
$userid = $allowed = null;
if (isset($auth)) $userid = $auth['User']['id'];
if (isset($acl)) $allowed = $acl->check(array('model' => 'User', 'foreign_key' => $userid), 'links/mod_edit');
  
if ($allowed != true) {
    return '';
} else { 
    $links = $this->requestAction('/mod/links/newlinks');
    if (count($links) > 0) { ?>
<h1 class="link">Ungeprüft</h1>
<div class="box"><ul>
<?php 
foreach ($links as $link) { ?>
 <li><?php echo $html->link(short($link['Link']['title'], 20), '/mod/links/edit/'.$link['Link']['id']); ?></li>
<?php } ?> 
</ul></div>
<?php }} ?>
