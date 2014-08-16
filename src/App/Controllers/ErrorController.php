<?php
namespace app\controllers;
use Framework\Core\Controller;

/**
 * Error Controller class file
 */
class ErrorController extends Controller {

	/**
	 * Action index
	 */
	public function actionIndex()
	{
		echo 'The requested action not found.';
	}
	
}