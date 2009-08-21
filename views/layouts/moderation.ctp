<?php echo '<?xml version="1.0" encoding="UTF-8"?>'?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
    <title>Admin: <?php echo $title_for_layout?></title>
    <?php echo $html->charset('utf-8');
          echo $html->css('moderation');
          echo $scripts_for_layout;
          if(isset($niceHead)) $niceHead->flush(); ?> 
</head>
<body>
<div id="head"><?php echo $html->link(
                  $html->image('logo.png', array('id'=>'logo', 'title'=>Configure::read('Site.Title'), 'alt'=>'')),
                  '/', array(), false, false)?></div>

<div id="navbar">
<p>
    <a class="home" href="/links/"><span>Home</span></a>
    <a class="links" href="/links/mod/links/"><span>Links</span></a>
    <a class="tags" href="/links/mod/tags/"><span>Tags</span></a>
</p>
</div>

<div id="content">
    <?php $session->flash() ?>  
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
