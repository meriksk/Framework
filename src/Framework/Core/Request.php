<?php
namespace Framework\Core;

/**
 * HTTP Request
 */
class Request extends Component
{
	
	const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_OVERRIDE = '_METHOD';

	/**
     * @var \Framework\Core\Request
     */
    protected static $request;
	
	/**
	 * Request properties
	 * @var array 
	 */
	private $prop = array();
	
	/**
     * Get request instance (singleton)
     *
     * @param bool $refresh Refresh properties
     * @return \Framework\Core\Request
     */
    public static function getInstance($refresh = false)
    {
        if (is_null(self::$request) || $refresh) {
            self::$request = new self();
        }

        return self::$request;
    }
	
	
	/**
     * Constructor
     */
    private function __construct()
    {
		//The HTTP request method
		$this->prop['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];

		//The IP
		$this->prop['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
		
		// Server params
		$scriptName = $_SERVER['SCRIPT_NAME']; // <-- "/foo/index.php"
		$requestUri = $_SERVER['REQUEST_URI']; // <-- "/foo/bar?test=abc" or "/foo/index.php/bar?test=abc"
		$queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ''; // <-- "test=abc" or ""

		// Physical path
		if (strpos($requestUri, $scriptName) !== false) {
			$path = $scriptName; // <-- Without rewriting
		} else {
			$path = str_replace('\\', '', dirname($scriptName)); // <-- With rewriting
		}
            
		$this->prop['SCRIPT_NAME'] = rtrim($path, '/'); // <-- Remove trailing slashes
		
		// Virtual path
		$this->prop['PATH_INFO'] = substr_replace($requestUri, '', 0, strlen($path)); // <-- Remove physical path
		$this->prop['PATH_INFO'] = str_replace('?' . $queryString, '', $this->prop['PATH_INFO']); // <-- Remove query string
		$this->prop['PATH_INFO'] = '/' . ltrim($this->prop['PATH_INFO'], '/'); // <-- Ensure leading slash
		
		// Query string (without leading "?")
		$this->prop['QUERY_STRING'] = $queryString;

		// Name of server host that is running the script
		$this->prop['SERVER_NAME'] = $_SERVER['SERVER_NAME'];

		// Number of server port that is running the script
		$this->prop['SERVER_PORT'] = $_SERVER['SERVER_PORT'];
		
		// Is the application running under HTTPS or HTTP protocol?
		$this->prop['url_scheme'] = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';
		
		// Error stream
		$this->prop['errors'] = @fopen('php://stderr', 'w');
    }
	
	/**
	 * Get environment properties
	 */
	public function getProperties()
	{
		return $this->prop;
	}

	/**
     * Get request method
     * @return string
     */	
	public function getMethod() 
	{
		return isset($this->prop['REQUEST_METHOD']) ? $this->prop['REQUEST_METHOD'] : self::METHOD_GET;
	}
	
	/**
     * Get Host
     * @return string
     */
    public function getHost()
    {
        if( isset($this->prop['HTTP_HOST']) ) {
            if (strpos($this->prop['HTTP_HOST'], ':') !== false) {
                $hostParts = explode(':', $this->prop['HTTP_HOST']);
                return $hostParts[0];
            }
			
            return $this->prop['HTTP_HOST'];
        }

        return $this->prop['SERVER_NAME'];
    }
	
	/**
     * Get Path Info
     * @return string
     */	
	public function getPathInfo() 
	{
		return isset($this->prop['PATH_INFO']) ? $this->prop['PATH_INFO'] : '/';
	}
}