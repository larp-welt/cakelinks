<?php 
if (!isset($paginator->params['paging'])) {
  return;
}
if (!isset($model) || $paginator->params['paging'][$model]['pageCount'] < 2) {
  return;
}
?>
<div class="pagination">
<div class="prev"><?php $paginator->options(array('url' => $this->passedArgs));
                        echo $paginator->prev('« Zurück ', null, null, array('class' => 'disabled')); ?></div>
<div class="next"><?php echo $paginator->next(' Weiter »', null, null, array('class' => 'disabled')); ?></div>
<div><?php echo $paginator->counter(array('format' => 'Seite %page% von %pages%')); ?></div>
</div><?php // } ?>