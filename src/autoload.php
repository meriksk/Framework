<?php

/**
 * You only need this file if you are not using composer.
 * Why are you not using composer?
 * https://getcomposer.org/
 */

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
  throw new Exception('This library requires PHP version 5.4 or higher.');
}

/**
 * PSR-0 autoloader
 */
function autoload($className) {
	$className = ltrim($className, '\\');
	$fileName  = __DIR__ . DIRECTORY_SEPARATOR;
	$namespace = '';

	if( $lastNsPos = strripos($className, '\\') ) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	//var_dump($fileName) . '<br />' . "\n";
	if (file_exists($fileName)) {
		require $fileName;
	}
}

// register autloader
spl_autoload_register('autoload');