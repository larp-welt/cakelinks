<?php
class SmartypantsHelper extends AppHelper {
    function __construct()
    {
        ob_start();
    }

    function __destruct()
    {
        App::import('Vendor', 'smartypants', array('file' => 'smartypants.php'));

		$output = ob_get_clean();
        $output = SmartyPants($output);

        ob_start();
        echo $output;
    }
}
?>