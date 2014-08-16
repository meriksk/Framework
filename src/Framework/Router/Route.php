<?php

namespace Framework\Router;

/**
 * Router
 */
class Route
{
	
	private $pattern;
	private $regex = '';
	private $params = array();
	private $methods = array();
	private $action;
	
	/**
     * Constructor
     */
	public function __construct($args) {
	
		$pattern = $action = null;
		
		// arg 0 - pattern/action function
		if( isset($args['pattern']) ) {
			$tmp = $args['pattern']; unset($args['pattern']);
		} else {
			$tmp = array_shift($args);
		}
		
		if( is_string($tmp) ) {
			$pattern = trim($tmp);
		} else if( is_action($tmp) ) {
			$pattern = call_user_func($tmp);
		}
		
		// arg 1 - action function/controller-action
		if( isset($args['action']) ) {
			$tmp = $args['action']; unset($args['action']);
		} else {
			$tmp = array_shift($args);
		}
		
		// router path (controller/action)
		if( is_array($tmp) && count($tmp)===2 ) {
			$action = $tmp;
		} else if( is_callable($tmp) ) {
			$action = $tmp;
		}
		
		// request method
		if( isset($args['method']) ) {
			$this->filter($args['method']);
		}
		
		// set data
		if( !empty($pattern) && !empty($action) ) {
			$this->pattern = $pattern;
			$this->regex = $this->compile($pattern);
			$this->action = $action;
		}
		
		return $this;
	}
	
	/**
	 * Class getter
	 * @param string $name
	 */
	public function __get($name) {
        if( method_exists($this, 'get'.  ucfirst($name)) ) {
			$name = 'get' . ucfirst($name);
			return $this->$name();
		} else {
			throw new Exception('Undefined index "' . $name . '".', $code);
		}
    }
	
	/**
	 * Filter by request type
	 * @return \Framework\Router\Route
	 */
	public function filter() {
		$args = func_get_args();
		$methods = array();
		
		if( !empty($args) ) {
			if( is_string($args) && !empty(trim($args)) ) {
				$methods[] = $args;
			} elseif( is_array($args) ) {
				$methods = array_merge($this->methods, $args);
				$methods = array_unique(array_filter($methods));
			}
			
			$this->methods = $methods;
		}
		
		return $this;
	}
	
	/**
     * Get supported HTTP methods
     * @return array
     */
    public function getHttpMethods()
    {
        return $this->methods;
    }
	
	/**
	 * Process pattern
	 * @param string $pattern
	 * @return string Route regex
	 */
	private function compile( $route ) {
		
		$pattern = '';
		$route = trim($route, '/');
				
		// Now let's actually compile the path 
		// The regular expression used to compile and match URL's
        if( preg_match_all('#([^\/]+)#i', $route, $matches, PREG_SET_ORDER) ) {

			$paramsCount = 0;
			
            foreach ($matches as $match) {
				
				$match = $match[1];
				if( !empty($match) ) {
					
					// named param
					if( $match{0} === ':' ) {
						
						// remove colon from param name
						$match = substr($match, 1);
						
						// parameter with regular expression
						if( strpos($match, '(') !== false && strpos($match, ')') !== false ) {
							
							$pattern .= '/' . substr($match, strpos($match, '('), strlen($match));
							$paramName = substr($match, 0, strpos($match, '('));
							$this->params[$paramsCount] = $paramName;
							++$paramsCount;
							
						// without regular expression
						} else {
							
							$pattern .= '/' . '([a-zA-Z0-9-_]+)';
							$this->params[$paramsCount] = $match;
							++$paramsCount;
							
						}
					} elseif( $match{0} === '(' && substr($match, -1) === ')' ) {
						
						$pattern .= '/' . $match;
						$this->params[$paramsCount] = null;
						++$paramsCount;
						
					} else {
						
						$pattern .= '/' . $match;
						
					}
				}
            }
			
			$pattern = '/' . trim($pattern, '/');
        }
		
		return '#^'. $pattern .'$#';
	}
	
	/**
	 * Get route regex
	 * @return string
	 */
	public function getRegex()
	{
		return $this->regex;
	}
	
	/**
	 * Get route action
	 * @return array|function
	 */
	public function getAction()
	{
		return $this->action;
	}
	
	/**
	 * Get route params
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}
	
	/**
	 * Get route param
	 * @param string $param
	 * @return mixed|false
	 */
	public function getParam($param)
	{
		return isset($this->params[$param]) ? $this->params[$param] : false;
	}
	
}
