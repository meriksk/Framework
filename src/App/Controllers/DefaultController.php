<?php
namespace app\controllers;
use Framework\Core\Controller;

class DefaultController extends Controller {

	/**
	 * Action index
	 */
	public function actionIndex()
	{
		print_r($this->response->code);
		echo 'index';
	}
	
}