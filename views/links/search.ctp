<?php $niceHead->css('links')?>
<h1><?php
      $userid = $ismod = null;
      if (isset($auth)) $userid = $auth['User']['id'];
      if (isset($acl)) $ismod = $acl->check(array('model' => 'User', 'foreign_key' => $userid), 'links/mod_edit');

      echo $html->link('Themen', '/');
      echo ' :: Suchen';
?></h1>

<div id="links">
<?php echo $this->element('basic_search'); ?>

<dl id="linklist">
<?php 
    if (!empty($results)) {
      foreach ($results as $link) { ?>
<dt><?php echo $html->link($link['Link']['title'], '/links/jumpto/'.$link['Link']['id'], array('target'=>'_blank'))?></dt>
<dd><?php if ($link['Link']['status'] == WARNING) { ?>
<p class="warning">Dieser Link steht unter Beobachtung durch unsere Moderatoren!</p>
<?php } ?><?php echo $this->element('linkbox', array('link'=>$link, 'userid'=>$userid, 'ismod'=>$ismod)); ?>
<?php echo $widgets->parse($link['Link']['description'])?>
<p class="subline"><?php
echo $html->link($link['Link']['url'], '/links/jumpto/'.$link['Link']['id'], array('target'=>'_blank', 'class'=>'url'))?> &middot;
Hits: <?php echo $link['Link']['hit_count']?></p>
<p class="tags"><?php
$tags = array();
foreach ($link['Tag'] as $tag):
    $tags[] = $html->link($tag['name'], '/links/index/'.$tag['slug']);
endforeach;
echo implode(', ', $tags);?>
</p>
</dd>
<?php 
    }
  } else { ?>
  <div class="message"><p>Leider hat die Suche kein Ergebnis erbracht!</p>
  <p>Bitte versuche die Suche anders zu formulieren.</p></div>
<?php } ?>
</dl>
</div>

<?php 
    
    echo $this->element('pagination', array('model'=>'Link')); ?>
