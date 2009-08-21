<h2 class="comments">Kommentare</h2>
<div id="comments">
<?php 
if (!empty($comments)) {
    foreach ($comments as $comment) { ?>
<div class="comment">
    <p class="meta"><b><?php echo $html->link($comment['User']['username'], '/profiles/view/'.$comment['User']['id']) ?></b><br />
    <?php echo date('d.m.Y', strtotime($comment['Comment']['created']))?><br />
	<?php echo date('H:i', strtotime($comment['Comment']['created']))?>
    </p>
    <div class="text">
    <h3><?php echo $comment['Comment']['title'] ?></h3>
    <?php echo $widgets->parse($comment['Comment']['comment']) ?>
    </div>
</div>
    <?php }
} else { ?>
    <div class="message">
      <p>Leider sind für diesen Link noch keine Kommentare vorhanden!</p>
      <p>Vielleicht möchtest Du den ersten Kommentar hinterlassen?</p>
    </div>
<?php 
} ?>
</div>