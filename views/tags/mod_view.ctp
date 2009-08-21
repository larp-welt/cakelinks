<?php 
    $niceHead->css('tags');

    $status = array(
        'stop.png', 
        'accept.png', 
        'warning.png', 
        'link_break.png', 
        'star.png',
        'del.png');

?>
<h1><?= htmlspecialchars($data['Tag']['name']) ?></h1>

<div id="tags">
<table id="modtable">
<tr><th>Link</th>
    <th>&nbsp;</th></tr>
<?php if (!empty($data['Link'])) { ?>
<?php 
    $row = 0;
    foreach ($data['Link'] as $link): ?>
<tr class="row<?=$row?>">
    <td class="first"><?php 
        echo $html->image('/img/'.$status[$link['status']]);
		echo '&nbsp;';
    	echo $html->link(htmlspecialchars($link['title']),
                         '/mod/links/edit/'.$link['id'], array(), false, false); ?></td>
    <td class="last" style="width: 2em;">&nbsp;</td>
</tr>
<?php 
    $row = 1-$row;
    endforeach; ?>
<?php } else { ?>
<tr class="row1 lower"><td colspan="2" class="first last">
<p class="message">Zur Zeit sind keine Links zum Tag "<?= htmlspecialchars($data['Tag']['name']) ?>" vorhanden!</p>
</td></tr>
<?php } ?>
</table>
</div>