<?php

namespace Framework\Web;

/**
 * Router
 */
class Headers extends \Framework\Core\Component
{
	
	/**
     * @var array
     */
    private $_headers = array();
	
    /**
     * Returns the named header(s).
     * @param string $name the name of the header to return
     * @param mixed $default the value to return in case the named header does not exist
     * @return string|array the named header(s).
     */
    public function get($name, $default = null)
    {
        $name = strtolower($name);
        if( isset($this->_headers[$name]) ) {
            return $this->_headers[$name];
        } else {
            return $default;
        }
    }
	
    /**
     * Adds a new header.
     * If there is already a header with the same name, it will be replaced.
     * @param string $name the name of the header
     * @param string $value the value of the header
     * @return static the collection object itself
     */
    public function set($name, $value = '')
    {
        $name = strtolower($name);
        $this->_headers[$name] = (array)$value;
        return $this;
    }
	
	/**
     * Adds a new header.
     * @param string $name the name of the header
     * @param string $value the value of the header
     * @return static the collection object itself
     */
    public function add($name, $value)
    {
        $name = strtolower($name);
        $this->_headers[$name][] = $value;
        return $this;
    }
	
	
}