<h1 class="link">Neuste Links</h1>
<div class="box"><ul>
<?php $links = $this->requestAction('/links/latest');
foreach ($links as $link) { ?>
 <li><?php echo $html->link(short($link['Link']['title'], 20), '/links/view/'.$link['Link']['id']); ?></li>
<?php } ?> 
</ul></div>
