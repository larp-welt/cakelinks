<?php

function strip_bbcode($string) {
    return preg_replace('/\[.+\]/', '', $string);
}

function truncate($text, $chars=100) {
    if (strlen($text) > $chars) {
        $text = $text." ";
        $text = substr($text,0,$chars);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
    }
    return $text;
}
    
?>
