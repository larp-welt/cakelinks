<?php $niceHead->css('links')?>
<?php $niceHead->css('forms')?>
<h1>Link anmelden</h1>
<div id="links">
<div class="links-info">
<p>Dein Link fehlt uns noch? Dann melde ihn einfach an, wir nehmen ihn
gerne in unser Verzeichnis auf!</p>

<p><strong>Hervorgehobenen</strong> Felder sind Pflichtangaben, und 
müssen von Dir ausgefüllt werden.</p>
</div>

<?php echo $this->element('linkform'); ?>
</div>