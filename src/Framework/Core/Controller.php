<?php
namespace Framework\Core;	

class Controller extends Component {

	/**
	 * @var \Framework\Core\Response
	 */
	private $response;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Call controller action
	 * @param string $action
	 * @param array $params
	 */
	public function callAction( $action, $params = array() ) 
	{
		$controllerAction = 'action' . ucfirst($action);
		//var_dump($params);
		echo $this->{$controllerAction}();
	}
	
	/**
	 * Get response
	 * @return \Framework\Core\Response
	 */
	public function getResponse()
	{
		return $this->response;
	}
	
	/**
	 * Set response
	 * @return \Framework\Core\Response
	 */
	public function setResponse(\Framework\Core\Response $response)
	{
		return $this->response = $response;
	}
}
