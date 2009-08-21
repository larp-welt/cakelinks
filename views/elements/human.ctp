<?php if (!isset($auth)) { ?>
<fieldset><legend>Spam-Schutz</legend>
<?php echo $widgets->input('human', array('label'=>'Ich bin wirklich ein Mensch!', 'type'=>'checkbox')); ?>
</fieldset>
<?php } else { ?>
<?php echo $widgets->input('human', array('value'=>'1', 'type'=>'hidden')); ?>
<?php } ?>