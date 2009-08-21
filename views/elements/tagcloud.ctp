<p id="bigcloud">
<?php

$intervall = Configure::read('Links.new_intervall'); 

function ageToColor($age, $intervall) {
		$relage = 1-$age/$intervall;
		$relage = ($relage < 0)? 0 : $relage;
       	return sprintf('%02x', 255*$relage);
}

list($cloud, $min, $max) = $this->requestAction('/tags/tagCountsActive');
if ($max == $min) {$min = $min - 0.1;}
if ($min >= 1) {$min = $min - 1;}
foreach ($cloud as $tag) {
    if ($tag[0]['cnt'] > 0) {
        $size = (int) (80+100*1/($max-$min)*($tag[0]['cnt']-$min));
		$color = ageToColor($tag[0]['age'], $intervall);

        $format = 'font-size: '.$size.'%; color: #'.$color.'0000;';
		$links = ($tag[0]['cnt']<>1) ? ' Links':' Link';

        echo $html->link(str_replace(' ', '&nbsp;', htmlspecialchars($tag['T']['name'])), 
                         '/links/index/'.$tag['T']['slug'], 
                         array('style'=>$format, 
                               'title'=>$tag[0]['cnt'].$links), null, false)." ";
    }
}  ?>
</p>
<div id="legend">
	<span style="text-align: left; color: #<?php echo ageToColor(0, $intervall); ?>0000; width: 25%; display: block; float: left;">Neue Links</span>
	<span style="text-align: center; color: #<?php echo ageToColor((int)($intervall/3), $intervall); ?>0000; width: 25%; display: block; float: left;"><?php echo (int)($intervall/3); ?> Tage alte Links</span>
	<span style="text-align: center; color: #<?php echo ageToColor((int)(2*$intervall/3), $intervall); ?>0000; width: 25%; display: block; float: left;"><?php echo (int)(2*$intervall/3); ?> Tage alte Links</span>
	<span style="text-align: right; color: #<?php echo ageToColor($intervall, $intervall); ?>0000; width: 25%; display: block; float: left;">keine neuen Links</span>
</div>