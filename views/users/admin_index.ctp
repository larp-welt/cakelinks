<?php 
    $niceHead->css('users');

    $disabled = array('accept.png', 'stop.png');
?>
<h1>Mitglieder</h1>

<div id="users">
<table id="modtable">
<tr><th>Status</th>
    <th>Username</th>
    <th>E-Mail</th>
    <th>Gruppe</th>
    <th>&nbsp;</th></tr>
<?php 
    $row = 0;
    foreach ($users as $user): ?>
<tr class="row<?=$row?>">
    <td class="first"><?php echo $html->image('/img/'.$disabled[$user['User']['disabled']]) ?></td>
    <td><?php echo $html->link($user['User']['username'], '/admin/users/edit/'.$user['User']['id']); ?></td>
    <td><?php echo $user['User']['email'] ?></td>
    <td><?php echo $user['Group']['name'] ?></td>
    <td class="last"><?php 
        echo $html->link($html->image('/img/pencil.png', array('title'=>'Bearbeiten', 'alt'=>'[Edit]')),
                         '/admin/users/edit/'.$user['User']['id'], array(), false, false);
        echo '&nbsp;';
        echo $html->link($html->image('/img/del.png', array('title'=>'Bearbeiten', 'alt'=>'[Löschen]')),
                         '/admin/users/delete/'.$user['User']['id'], array(), false, false);
        ?></td>
</tr>
<?php 
    $row = 1-$row;
    endforeach; ?>
</table>
<?php echo $this->element('pagination', array('model'=>'User')); ?>
</div>
