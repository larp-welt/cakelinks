<?php echo '<?xml version="1.0" encoding="UTF-8"?>'?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
    <title>Admin: <?php echo $title_for_layout?></title>
    <?php echo $html->charset('utf-8');
          echo $html->css('admin');
          echo $scripts_for_layout;
          if(isset($niceHead)) $niceHead->flush(); ?> 
</head>
<body>
<div id="head"><?php echo Configure::read('Site.Title')?>: Administration</div>

<div id="navbar">
<a class="panelhead">Links</a>
<p>
    <a class="links"><span>Links</span></a>
    <a class="bin"><span>Papierkorb</span></a>
    <a class="link"><span>Links ohne Tags</span></a>
    <a  class="rebuild" href="/links/admin/desktop/recreateIndex"><span>Index neu generieren</span></a>
</p>

<a class="panelhead">Tags</a>
<ul>
    <li>Tags</li>
    <li>Tags ohne Links</li>
</ul>

<a class="panelhead">Mitgliederverwaltung</a>
<p>
    <a class="users" href="/links/admin/users/index"><span>Mitglieder</span></a>
    <a class="addusers" href="/links/admin/users/add"><span>Mitglied anlegen</span></a>
</p>

<a class="panelhead">Kommentare</a>
<ul>
    <li>Kommentare</li>
    <li>Index neu genierieren</li>
</ul>

<a class="panelhead">System</a>
<p>
    <a class="info" href="/links/admin/desktop/info"><span>Mitteilung</span></a>
</p>
</div>

<div id="content">
    <?php $session->flash(); ?>  
    <?php echo $content_for_layout ?>
</div>

<div id="foot">
<div class="copyright">&copy; 2000-<?php echo date('Y')?> by <?php echo Configure::read('Site.Title')?></div>
<div class="buttons"><?php echo $html->link(
                                $html->image('cake.power.gif', array('title'=>'', 'alt'=>'cakePHP')),
                                'http://www.cakephp.org/', array(), false, false)?></div>
</div>
</body>
</html>
