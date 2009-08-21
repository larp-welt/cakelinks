<?php 
class MailtoHelper extends AppHelper
{
	function encode($mail, $text="", $class="", $params=array())
	{
		$encmail ="";
		for($i=0; $i<strlen($mail); $i++)
		{
			$encMod = rand(0,2);
	        switch ($encMod) {
	        case 0: // None
	            $encmail .= substr($mail,$i,1);
	            break;
	        case 1: // Decimal
	            $encmail .= "&#".ord(substr($mail,$i,1)).';';
	            break;
	        case 2: // Hexadecimal
				$encmail .= "&#x".dechex(ord(substr($mail,$i,1))).';';
	            break;
			}
		}

		if(!$text)
		{
			$text = $encmail;
		}
		$encmail = "&#109;&#97;&#105;&#108;&#116;&#111;&#58;".$encmail;
		$querystring = "";
		foreach($params as $key=>$val)
		{
			if($querystring){
				$querystring .= "&$key=".rawurlencode($val);
			} else {
				$querystring = "?$key=".rawurlencode($val);
			}
		}
		return "<a class='$class' href='$encmail$querystring'>$text</a>";
	}
}
?>