<?php
  $userid = $allowed = null;
  if (isset($auth)) $userid = $auth['User']['id'];
  if (isset($acl)) $allowed = $acl->check(array('model' => 'User', 'foreign_key' => $userid), 'comments/add');
  
  if ($allowed) {
?>
<?php echo $widgets->create('Comment', array('id'=>'commentform'));
$niceHead->css('forms');

echo $widgets->input('id');
echo $widgets->input('parent_model', array('type'=>'hidden', 'value'=>$parent_model));
echo $widgets->input('parent_id', array('type'=>'hidden', 'value'=>$parent_id));
?>

<fieldset><legend>Kommentar</legend>
<?php echo $widgets->input('title', array('label'=>'Titel'));
      echo $widgets->editor('comment', array('label'=>false, 
                                             'set'=>'bbcode', 
                                             'skin'=>'simple',
                                             'parser' => '/comments/preview/bbcode' ));
?>
</fieldset>

<?php echo $widgets->end('Kommentar speichern');
} ?>