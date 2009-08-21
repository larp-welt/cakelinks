<h1 class="comments">Neuste Kommentare</h1>
<div class="box"><ul>
<?php $comments = $this->requestAction('/comments/latest');
foreach ($comments as $comment) { ?>
 <li><?php echo $html->link(short($comment['Comment']['title'], 20), '/links/view/'.$comment['Comment']['parent_id']); ?></li>
<?php } ?> 
</ul></div>
