<h1 class="link">Larpkalender</h1>
<div class="box"><ul>
<?php $links = $this->requestAction('/larpkalenders/index');
$count = 0;
foreach ($links as $link) {
    if ($count < 5) { ?>
 <li><?php echo $html->link(short($link['title'], 40), $link['link']); ?></li>
<?php }
    $count++;
} ?> 
</ul>
<p class="small">&copy; bei LARPkalender</p>
</div>
