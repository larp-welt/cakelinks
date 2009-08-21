<?php 
    $niceHead->css('tags');

?>
<h1>Tags</h1>

<div id="tags">
<table id="modtable">
<tr><th>Tag</th>
    <th># Links</th>
    <th>&nbsp;</th></tr>
<?php if (!empty($tags)) { ?>
<?php 
    $row = 0;
    foreach ($tags as $tag): ?>
<tr class="row<?=$row?>">
    <td class="first"><?php 
    	echo $html->image('/img/tag.png');
		echo '&nbsp;';
    	echo $html->link(htmlspecialchars($tag['T']['name']),
                         '/mod/tags/view/'.$tag['T']['slug'], array(), false, false); ?></td>
    <td style="width: 3em;"><?php echo $tag['0']['cnt']; ?></td>
    <td class="last" style="width: 2em;"><?php
	    if ($tag['T']['id'] != 0) { 
			echo $html->link($html->image('/img/pencil.png', array('title'=>'Bearbeiten', 'alt'=>'[Edit]')),
                         '/mod/tags/edit/'.$tag['T']['id'], array(), false, false);
			echo '&nbsp;';
			echo $html->link($html->image('/img/del.png', array('title'=>'Löschen', 'alt'=>'[Delete]')),
                         '/mod/tags/delete/'.$tag['T']['id'], array(), false, false);
		} else {
			echo '&nbsp;';
		}
    ?>
    </td>
</tr>
<?php 
    $row = 1-$row;
    endforeach; ?>
<?php } else { ?>
<tr class="row1 lower"><td colspan="3" class="first last">
<p class="message">Zur Zeit sind keine Tags zur Moderation vorhanden!</p>
</td></tr>
<?php } ?>
</table>
<?php echo $this->element('pagination', array('model'=>'Tag')); ?>
</div>