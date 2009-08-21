<?php $niceHead->css('links');
      $niceHead->css('print_links', array('media'=>'print'));

      $userid = $ismod = null;
      if (isset($auth)) $userid = $auth['User']['id'];
      if (isset($acl)) $ismod = $acl->check(array('model' => 'User', 'foreign_key' => $userid), 'links/mod_edit');

	  $intervall = Configure::read('Links.new_intervall'); 

	  function ageToColor($age, $intervall) {
			$relage = 1-$age/$intervall;
			$relage = ($relage < 0)? 0 : $relage;
       		return sprintf('%02x', 255*$relage);
	  }

?>
<h1><?php
    echo $html->link('Themen', '/');
    echo ' :: '.$tagname;
?></h1>

<div id="links">
<dl id="linklist">
<?php echo $this->element('basic_search'); ?>
<?php foreach ($links as $link): ?>
<span class="xfolkentry">
<dt><?php echo $html->link($link['Link']['title'], '/links/jumpto/'.$link['Link']['id'], array('target'=>'_blank', 'class' => 'taggedlink'))?><?php
if ($link['Link']['age'] < $intervall) {
	$color = ageToColor($link['Link']['age'], $intervall);
	echo '&nbsp;<sup style="color: #'.$color.'0000;">neu</sup>';
}
?></dt>
<dd><?php if ($link['Link']['status'] == WARNING) { ?>
<p class="warning">Dieser Link steht unter Beobachtung durch unsere Moderatoren!</p>
<?php } ?><?php echo $this->element('linkbox', array('link'=>$link, 'userid'=>$userid, 'ismod'=>$ismod)); ?>
<span class="description"><?php echo $widgets->parse($link['Link']['description'])?></span>
<p class="subline"><?php
echo $html->link($link['Link']['url'], '/links/jumpto/'.$link['Link']['id'], array('target'=>'_blank', 'class'=>'url'))?> &middot;
Hits: <?php echo $link['Link']['hit_count']?></p>
<p class="tags"><?php
$tags = array();
foreach ($link['Tag'] as $tag):
    $tags[] = $html->link($tag['name'], '/links/index/'.$tag['slug'], array('rel'=>'tag'));
endforeach;
echo implode(', ', $tags);?>
</p>
</dd>
</span>
<?php endforeach; ?>
</dl>
</div>

<?php echo $this->element('pagination', array('model'=>'Link')); ?>
