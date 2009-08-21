<?php
    
    function format_date($original='', $format="%m/%d/%Y") {
        $format = ($format=='date' ? "%m-%d-%Y" : $format);
        $format = ($format=='datetime' ? "%m-%d-%Y %H:%M:%S" : $format);
        $format = ($format=='mysql-date' ? "%Y-%m-%d" : $format);
        $format = ($format=='mysql-datetime' ? "%Y-%m-%d %H:%M:%S" : $format);
        return (!empty($original) ? strftime($format, strtotime($original)) : "" );
    }

?>
