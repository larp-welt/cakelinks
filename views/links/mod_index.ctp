<?php 
    $niceHead->css('links');

    $status = array(
        'stop.png', 
        'accept.png', 
        'warning.png', 
        'link_break.png', 
        'star.png',
        'del.png');
?>
<h1>Links</h1>

<div id="links">
<?php echo $widgets->create(array('url'=>'/mod/links/index', 'id'=>'linkform')); ?>
<table id="modtable">
<tr><th>Status</th>
    <th>Seite</th>
    <th>Tags</th>
    <th>Melder</th>
    <th>&nbsp;</th></tr>
<?php if (!empty($links)) { ?>
<?php 
    $row = 0;
    foreach ($links as $link): ?>
<tr class="row<?=$row?>">
    <td class="first"><?php echo $html->image('/img/'.$status[$link['Link']['status']])?></td>
    <td><?php 
        echo $html->link($html->image('/img/pencil.png', array('title'=>'Bearbeiten', 'alt'=>'[Edit]')),
                         '/mod/links/edit/'.$link['Link']['id'], array(), false, false);
        echo '&nbsp;';
        echo $html->link($link['Link']['title'], $link['Link']['url'], array('target'=>'_blank')); ?></td>
    <td><?php
    $tags = array();
    foreach ($link['Tag'] as $tag):
        $tags[] = $tag['name'];
    endforeach;
    echo implode(', ', $tags);?></td>
    <td><?php echo $link['User']['username'] ?></td>
    <td class="last"><?php 
      echo $widgets->input('Link.action.'.$link['Link']['id'], array('options'=>array(
              'null'=>'',
              'publish'=>'Veröffentlichen',
              'reject'=>'Ablehnen'),
              'label'=>false)
            );
      echo $widgets->input('Link.email.'.$link['Link']['id'], array(
              'label'=>'Mail schicken',
              'type'=>'checkbox'));
    ?>
    </td>
</tr>
<tr class="row<?=$row?> lower">
    <td class="first">&nbsp;</td>
    <td colspan="3"><?php echo $widgets->parse($link['Link']['description'])?></td>
    <td class="last">&nbsp;</td>
</tr>
<?php 
    $row = 1-$row;
    endforeach; ?>
<?php } else { ?>
<tr class="row1 lower"><td colspan="5" class="first last">
<p class="message">Zur Zeit sind keine Einträge zur Moderation vorhanden!</p>
</td></tr>
<?php } ?>
</table>
<?php echo $this->element('pagination', array('model'=>'Link')); ?>
<?php echo $widgets->end('Links moderieren')?>
</div>
