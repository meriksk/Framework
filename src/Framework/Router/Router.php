<?php

namespace Framework\Router;

/**
 * Router
 */
class Router
{
    /**
     * @var Route The current route (most recently dispatched)
     */
    protected $currentRoute;
	
    /**
     * @var array Lookup hash of all route objects
     */
    public $routes;

    /**
	 * Route objects that match the request URI (lazy-loaded)
     * @var \Framework\Router\Route
     */
    protected $matchedRoute;
	
	/**
	 * @var object|array
	 */
	protected $action;
	
	/**
	 * @var array 
	 */
	protected $errorAction = array('Error', 'index');
	
	/**
	 * @array 
	 */
	protected $routeParams;


	/**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = array();
    }
	
	/**
	 * Load routes map from config file
	 */
	private function mapRoutesFromConfig()
	{
		if( file_exists(APP_CFG_DIR.DS.'routes.php') ) {
			$tmp = include APP_CFG_DIR.DS.'routes.php';
			$this->mapRoutes( $tmp );
		}
	}

	/**
	 * Map routes
	 * @param array $routes
	 */
	public function mapRoutes($routes) {
		if( !empty($routes) && is_array($routes) ) {
			foreach( $routes as $args ) {
				$route = new \Framework\Router\Route($args);
				$this->add($route);
			}
		}
	}
	
    /**
     * Add a route object to the router
     * @param \Framework\Router\Route $route Route
     */
    public function add(\Framework\Router\Route $route)
    {
		$this->routes[] = $route;
        return $this;
    }
	
	/**
	 * Dispatch request
	 * @param \Framework\Core\Request $request
	 */
	public function dispatch( \Framework\Core\Request $request ) 
	{
		// merge routes from config
		$this->mapRoutesFromConfig();
		
		// match route
		$this->mathRoute( $request );
		
		// response
		$response = new \Framework\Core\Response;
		
		// url not match
		if( empty($this->matchedRoute) ) {
			
			$response->setStatus(404);
			
			if( is_callable('routeNotMatch') ) {
				routeNotMatch( $request->getProperties() );
			} else {
				include APP_DIR . DS . 'controllers' . DS . 'ErrorController.php';
				$controller = new \App\Controllers\ErrorController;
				$controller->response = $response;
				$controller->callAction('index');
			}
			
		} else {
			
			// callback function
			if( is_callable($this->matchedRoute->action) ) {
				call_user_func_array($this->matchedRoute->action, $this->routeParams);
			// controller/action
			} elseif( is_array($this->matchedRoute->action) ) {
				
				$controller = $this->matchedRoute->action[0];
				$action = $this->matchedRoute->action[1];
				
				$path = APP_DIR . DS . 'controllers' . DS . $controller. 'Controller.php';
				if( file_exists($path) ) {
					include $path;
					$tmp = '\App\Controllers\\'. $controller .'Controller';
					$controller = new $tmp;
					$controller->response = $response;
					$controller->callAction('index', $this->routeParams);
				} else {
					
				}
				
			// callback function not found
			} else {
				
			}
			
		}
	}
	
	/**
	 * Match request
	 * @param string $path
	 * @return \Framework\Router\Route $route
	 */
	private function mathRoute( \Framework\Core\Request $request ) {
		
		$method = $request->getMethod();
		$path = $request->getPathInfo();
		
		if( !empty($this->routes) ) {
			foreach( $this->routes as $route ) {
				
				//echo ' path: '. $path . "\t\t\t" . ' pattern: ' . $route->regex . "\n";
				
				if( preg_match($route->regex, $path, $matched) ) {
				
					// request methods
					$allowedMethods = $route->getHttpMethods();
					if( !in_array($method, $allowedMethods) ) { continue; }
					
					$i = 1;
					$params = $route->getParams();
					array_shift($matched);
					
					foreach( $params as $id => $name ) {
						$name = !empty($name) ? $name : 'param' . $i;
						$this->routeParams[$name] = $matched[$id];
						++$i;
					}
					
					// set action
					$this->action = $route->getAction();
					
					// matched route
					$this->matchedRoute = $route;
					break;
				}
				
			}//foreach
		}
	}
	
	
}