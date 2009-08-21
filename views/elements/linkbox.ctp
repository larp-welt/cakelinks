<?php
    
  $edit = array(
      'owner' => array(
            'title' => 'Bearbeiten',
            'url' => array('controller'=>'links', 'action'=>'edit/{linkid}'),
            'rule' => array('eq', '{owner}', $userid)
            ),
       'moderator' => array(
            'title' => 'Bearbeiten',
            'url' => '/mod/links/edit/{linkid}',
            'rule' => array('eq', true, $ismod)
            )
  );
?>
<div class="sidebox">
<ul>
<li><?php echo $html->link('Details', '/links/view/'.$link['Link']['id'], array('class'=>'view'))?></li>
<li><?php echo $html->link($link['Link']['comment_count'].'&nbsp;Kommentare', '/links/view/'.$link['Link']['id'].'#comments', 
                           array('class'=>'comments'), null, false)?></li>
<li><?php echo $html->link('Melden', '/links/broken/'.$link['Link']['id'], array('class'=>'broken'))?></li>
<?php 
    $editlink = $widgets->modlink($edit, array('owner'=>$link['Link']['user_id'], 'linkid'=>$link['Link']['id']), 
                              array('class'=>'edit'), false);
    if ($editlink != '') echo '<li>'.$editlink.'</li>';?>
</ul>
</div>