<?php $niceHead->css('tags')?>
<?php $niceHead->css('forms')?>
<h1>Tag bearbeiten</h1>
<div id="tags">

<?php echo $widgets->create(array('url'=>'/mod/tags/edit/'.$data['Tag']['id'], 'id'=>'tagform'));
echo $widgets->input('id'); ?>

<fieldset><legend>Tag</legend>
<?php echo $widgets->input('name', array('label'=>'Tag')); ?>
</fieldset>

<?php echo $widgets->end('Link speichern')?>

</div>