<?php $niceHead->css('tags')?>
<?php $niceHead->css('forms')?>
<h1>Tag löschen: "<?= htmlspecialchars($data['Tag']['name']) ?>"</h1>
<div id="tags">

<?php echo $widgets->create(array('id'=>'tagform', 'action'=>'mod_delete'));
echo $widgets->input('id');
?>

<fieldset><legend>Tag-Behandlung</legend>
<p>Wie sollen Links behandelt werden, die dem Tag zugeordnet sind?</p>
<?php echo $widgets->input('new', array('label'=>'Ersatz-Tag', 'options'=>$tags, 'default'=>0)); ?>
</fieldset>

<?php echo $widgets->end('Tag löschen')?>

</div>