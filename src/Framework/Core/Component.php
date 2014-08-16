<?php

namespace Framework\Core;
use \Exception;

class Component
{
	
	/**
	 * Returns the value of a component property.
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
        $getter = 'get' . ucfirst($name);
		if( method_exists($this, $getter) ) {
            // read property, e.g. getName()
			return $this->$getter();
        } else {
			throw new Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
		}
    }
	
	/**
	 * Sets the value of a component property.
	 * @param string $name
	 * @param mixed $value the property value
	 * @return mixed
	 * @see __get()
	 */
	public function __set($name, $value) {
        $setter = 'set' . $name;
		if( method_exists($this, $setter) ) {
            // set property
			$this->$setter($value);
        } else {
			throw new Exception('Setting unknown property: ' . get_class($this) . '::' . $name);
		}
    }
	
}

