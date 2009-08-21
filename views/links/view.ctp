<?php $niceHead->css('links'); 
      $niceHead->css('comments');
      $niceHead->css('print_links', array('media'=>'print'));

      $userid = $ismod = null;
      if (isset($auth)) $userid = $auth['User']['id'];
      if (isset($acl)) $ismod = $acl->check(array('model' => 'User', 'foreign_key' => $userid), 'links/mod_edit');
      
?>
<h1><?php echo $link['Link']['title']?></h1>

<div id="links" class="xfolkentry">
<div id="linklist"><?php if ($link['Link']['status'] == WARNING) { ?>
<p class="warning">Dieser Link steht unter Beobachtung durch unsere Moderatoren!</p>
<?php } ?><?php echo $this->element('linkbox', array('link'=>$link, 'userid'=>$userid, 'ismod'=>$ismod));
      echo $widgets->parse($link['Link']['description'])?>

<p class="subline">
<div class="tags"><?php
$tags = array();
foreach ($link['Tag'] as $tag):
    $tags[] = $html->link($tag['name'], '/links/index/'.$tag['slug'], array('rel'=>'tag'));
endforeach;
echo implode(', ', $tags);
?></div>
</p>


<table>
    <tr><th>Adresse</th><td colspan="3"><?php echo $html->link($link['Link']['url'], 
                                                   '/links/jumpto/'.$link['Link']['id'], 
                                                   array('target'=>'_blank', 'class'=>'url taggedlink', 'title'=>$link['Link']['title']))?></td></tr>
    <tr><th>Gemeldet von</th><td><?php echo $html->link($link['User']['username'], '/profiles/view/'.$link['User']['id']) ?></td>
        <th>Gemeldet am</th><td><?php echo date('d.m.Y', strtotime($link['Link']['created']))?></td></tr>
    <tr><th>Startdatum</th><td><?php echo $link['Link']['start']?></td>
        <th>Enddatum</th><td><?php echo $link['Link']['end']?></td></tr>
    <tr><th>Hits</th><td><?php echo number_format($link['Link']['hit_count'],0,',','.') ?> (<?php echo number_format($link['Link']['hits_per_day'],2,',','.'); ?> pro Tag)</td></tr>
</table>

    
<?php
    echo $this->element('comments', array('comments'=>$comments));
    echo $this->element('pagination', array('comments'=>$comments, 'model'=>'Comment'));
    echo $this->element('commentform', array('parent_id'=>$link['Link']['id'], 'parent_model'=>'Links')); 
?>

</div>
</div>