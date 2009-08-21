<?php $niceHead->css('tags')?>
<h1>Themen</h1>

<div id="tags">
<?php echo $this->element('basic_search'); ?>

<?php echo $this->element('tagcloud', array('cache'=>'15 min')); ?>

<?php
$file = CAKE_CORE_INCLUDE_PATH.DS.APP_DIR.DS.'tmp'.DS.'cache'.DS.'info'.DS.'info.bbcode';
$info = file_get_contents($file);
if (!empty($info)) {
?><div class="message">
<?php echo $widgets->parse($info); ?>
</div><?php } ?>
</div>