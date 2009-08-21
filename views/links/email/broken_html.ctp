<p>Hallo!</p>

<p>Der Link "<a href="<?php echo $data['Link']['url']?>"><?php echo $data['Link']['title']?></a>" wurde
uns als <?php
switch ($data['Link']['error']) {
    case '404': echo 'ungültig'; break;
    case 'law': echo 'rechtlich bedenklich'; break;
    case 'noLarp': echo 'keine LARP-Seite'; break;
}
?> gemeldet.</p>

<blockquote><?php echo $widgets->parse($data['Link']['message'])?></blockquote>

<p>Tschüss und Danke<br />
LARP-Welt</p>

