<?php

    
    function spell($string) {
        $z = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $w = array('eins', 'zwei', 'drei', 'vier', 'fuenf', 'sechs', 'sieben', 'acht', 'neun', 'null');
        
        return str_replace($z, $w, $string);
    }
    
    function despell($string) {
        $z = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $w = array('eins', 'zwei', 'drei', 'vier', 'fuenf', 'sechs', 'sieben', 'acht', 'neun', 'null');
        
        return str_replace($w, $z, $string);
    }
    
?>
