Hallo!

Der Link "<?php echo $data['Link']['title']?>" (<?php echo $data['Link']['url']?>) wurde
uns als <?php
switch ($data['Link']['error']) {
    case '404': echo 'ungültig'; break;
    case 'law': echo 'rechtlich bedenklich'; break;
    case 'noLarp': echo 'keine LARP-Seite'; break;
}
?> gemeldet.

<?php echo $data['Link']['message'] ?>

Tschüss und Danke
LARP-Welt

