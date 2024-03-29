<?php
/**
 *	NiceHead helper
 *	@author Kim Biesbjerg
 * 	@desc 	This helper can inject CSS/JS into the head of your layout
 * 			and autoload CSS/JS based on current controller/action
 *
 * 			Requires PrototypeJS and Dan Webb's DomReady to function properly.
 * 			Prototype: www.prototypejs.org
 * 			DomReady: http://smoothoperatah.com/files/onDOMReady.js
 * 	@version 19. april, 2007
 */
class NiceHeadHelper extends Helper
{
	/**
	 * Autoload configuration
	 *
	 * Put files in your CSS/JS
	 * /app/webroot/css|js/controller/controller.css|controller_action.css
	 * /app/webroot/themed/theme/css|js/controller/controller.css|controller_action.css
	 *
	 */
	var $autoloadCss = true;
	var $autoloadJs = true;

	/**
	 * We use Cake's own Html/Javascript helpers
	 * to generate tags to wrap around registered items
	 *
	 * @var array
	 */
	var $helpers = array('Html', 'Javascript');

	/**
	 * Order to flush registered items in <head>
	 *
	 * @var array
	 */
	var $priority = array('js', 'css', 'jsOnReady', 'jsOnLoad', 'jsBlock', 'cssBlock', 'raw');

	/**
	 * Holds our registered items
	 *
	 * @var array
	 */
	var $_registered = array();

	function __construct()
	{
		   static $library = array();
		   $this->_registered =& $library;
	}

	function beforeRender()
	{
		$this->_autoload();
	}

	/**
	 * Function to check if file exists and autoload
	 * if $autloadCss/$autoloadJs is set to true
	 */
	function _autoload()
	{
		/**
		 * Get current controller and action
		 */
		$controller = $this->params['controller'];
		$action = $this->params['action'];

		/**
		 * Check if we are supposed to autoload controller/action css
		 */
		if($this->autoloadCss)
		{
			/**
			 * CSS base paths
			 */
			$themedCssPath = WWW_ROOT . $this->themeWeb . CSS_URL . $controller . DS;
			$commonCssPath = WWW_ROOT . CSS_URL . $controller . DS;

			/**
			 * Check if CSS file for current controller exists
			 */
			if(file_exists($themedCssPath . $controller . '.css') || file_exists($commonCssPath . $controller . '.css'))
			{
				$this->css($controller . DS . $controller);
			}

			/**
			 * Check if CSS file for current action exists
			 */
			if(file_exists($themedCssPath . $controller . '_' . $action . '.css') || file_exists($commonCssPath . $controller . '_' . $action . '.css'))
			{
				$this->css($controller . DS . $controller . '_' . $action);
			}
		}

		/**
		 * Check if we are supposed to autoload controller/action js
		 */
		if($this->autoloadJs)
		{
			/**
			 * JS base paths
			 */
			$themedJSPath = WWW_ROOT . $this->themeWeb . JS_URL . $controller . DS;
			$commonJSPath = WWW_ROOT . JS_URL . $controller . DS;

			/**
			 * Check if JS file for current controller exists
			 */
			if(file_exists($themedJSPath . $controller . '.JS') || file_exists($commonJSPath . $controller . '.JS'))
			{
				$this->js($controller . DS . $controller);
			}

			/**
			 * Check if JS file for current action exists
			 */
			if(file_exists($themedJSPath . $controller . '_' . $action . '.js') || file_exists($commonJSPath . $controller . '_' . $action . '.js'))
			{
				$this->js($controller . DS . $controller . '_' . $action);
			}
		}
	}

	/**
	 * Includes a block of javascript on dom load
	 *
	 * @param string $input
	 */
	function jsOnReady($input)
	{
		$this->_register($input, 'jsOnReady');
	}

	/**
	 * Includes a block of javascript on window load
	 *
	 * @param string $input
	 */
	function jsOnLoad($input)
	{
		$this->_register($input, 'jsOnLoad');
	}

	/**
	 * Includes an external javascript file
	 *
	 * @param string $input
	 */
	function js($input)
	{
		$this->_register($input, 'js');
	}

	/**
	 * Includes a block of javascript
	 *
	 * @param string $input
	 */
	function jsBlock($input)
	{
		$this->_register($input, 'jsBlock');
	}

	/**
	 * Includes an external stylesheet
	 *
	 * @param string $input
	 */
	function css($input, $options=null)
	{
		$this->_register($input, 'css', $options);
	}

	/**
	 * Includes a block of styles
	 *
	 * @param string $input
	 */
	function cssBlock($input)
	{
		$this->_register($input, 'cssBlock');
	}

	function raw($input)
	{
		$this->_register($input, 'raw');
	}

	/**
	 * Internal function used to register items
	 *
	 * @param string $item
	 * @param string $type
	 */
    function _register($item, $type, $options=null)
    {
    	if(!array_key_exists($type, $this->_registered))
    	{
    		$this->_registered[$type] = array();
    	}

    	if(!in_array($item, $this->_registered[$type]))
        {
            $this->_registered[$type][] = array($item,$options);
        }
    }

	/**
	 * Output the registered items
	 *
	 */
    function flush()
    {
    	foreach($this->priority as $type)
    	{
    		if(array_key_exists($type, $this->_registered))
    		{
    			$items = $this->_registered[$type];

    			switch($type)
	    		{
				case 'css':
	    				foreach($items as $item)
	    				{
	    					e($this->Html->css($item[0], null, $item[1]));
	    				}
	    				break;
	    			case 'js':
	    				foreach($items as $item)
	    				{
	    					e($this->Javascript->link($item[0]));
	    				}
	    				break;
	    			case 'raw':
	    				foreach($items as $item)
	    				{
	    					e($item[0]);
	    				}
	    				break;
	    			case 'jsOnReady':
                        $tmp = array();
  	                    foreach ($items as $i) $tmp[] = $i[0];
                        // ME: Hackend for jQuery
			   		    //$output  = "Event.onDOMReady(function(){";
                        $output  = "$(document).ready(function(){";
						$output .= join($tmp);
						$output .= "});";
						e($this->Javascript->codeBlock($output));
						break;
	    			case 'jsOnLoad':
                                                $tmp = array();
                                                foreach ($items as $i) $tmp[] = $i[0];
						$output  = "Event.observe(window, 'load', function(){";
						$output .= join($tmp);
						$output .= "});";
						e($this->Javascript->codeBlock($output));
						break;
	    			case 'jsBlock':
                                                $tmp = array();
                                                foreach ($items as $i) $tmp[] = $i[0];
						$output = join($tmp);
						e($this->Javascript->codeBlock($output));
						break;
	    			case 'cssBlock':
                                                $tmp = array();
                                                foreach ($items as $i) $tmp[] = $i[0];
						$output = join($tmp);
						e($this->Html->css($output));
						break;
	    			default:
	    				die("Internal error. Unknown type: '{$type}'");
	    		}
    		}

    	}
    }
}
?>
