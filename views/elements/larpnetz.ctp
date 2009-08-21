<?php
$members = array(
    'http://www.larpkalender.de/'=>'LARP-Kalender',
    'http://www.larpinfo.de/'=>'LARPInfo',
    'http://www.larpfaq.de/'=>'LARPfaq',
    'http://www.larp-bilder.de/'=>'LARP-Bilder',
    'http://www.larpchat.de/'=>'LARP-Chat',
    'http://www.larp-planung.de/'=>'LARP-Planung',
    'http://www.larpwiki.de/'=>'LARPwiki',
    'http://www.larpzeit.de/'=>'LARPzeit',
    );

$m = array();
foreach ($members as $url => $title) {
    $m[] = sprintf('<div class="member"><a href="%s">%s</a></div>', $url, $title);
}

$ml = implode('<div class="sep">&middot;</div>', $m);
    
?>
<div id="larpnetz">
<!-- Logo von http://www.everaldo.com/, Lizenz: LGPL -->
<a class="netzlogo" href="http://www.larp-netz.de/">proud member of LARP-Netz</a>
<?php echo $ml ?>
</div>