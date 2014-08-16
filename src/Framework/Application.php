<?php
namespace Framework;

/**
 * 
 */
class Application {

    /**
     * @const string
     */
    const VERSION = '0.0.1';
	
	/**
     * Objects holder
     */
	private $objHolder = array();
	
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		
		// defines
		defined('DS') or define('DS', DIRECTORY_SEPARATOR);
		defined('APP_DIR') or define('APP_DIR', realpath(__DIR__ . DS . '..' . DS . 'App'));
		defined('APP_CFG_DIR') or define('APP_CFG_DIR', APP_DIR . DS . 'Config');
		
		// Router
		$this->router = new \Framework\Router\Router;
		$this->request = \Framework\Core\Request::getInstance();
	}
	
	public function __get($name) {
        return $this->objHolder[$name];
    }
	
	public function __set($name, $value) {
        $this->objHolder[$name] = $value;
    }

    public function __isset($name) {
        return isset($this->objHolder[$name]);
    }

    public function __unset($name) {
        unset($this->objHolder[$name]);
    }
	
	/**
	 * Run app
	 */
	public function run() {
		
		// dispatch request
		$this->router->dispatch( $this->request );
		
	}
	
	
	// -------------------------------------------------------------------------
	// ROUTING
	// -------------------------------------------------------------------------
	
	private function addRoute($route) {
		return $this->router->add($route);
	}
	
	private function newRoute($args) {
		return new \Framework\Router\Route($args);
	}
	
	/**
     * Add GET route
     * @see {{:link mapRoute()}}
     * @return \Framework\Router\Route
     */
    public function get() {
        $args = func_get_args();
		$route = $this->newRoute($args)->filter(\Framework\Core\Request::METHOD_GET, \Framework\Core\Request::METHOD_HEAD);
        return $this->addRoute($route);
    }
	
	
}