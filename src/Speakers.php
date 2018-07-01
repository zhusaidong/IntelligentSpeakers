<?php
/**
* Speakers
*
* @author zhusaidong [zhusaidong@gmail.com]
*/
abstract class Speakers
{
	protected $request = NULL;
	protected $debug = FALSE;
	
	public function __construct($debug = FALSE)
	{
		$this->debug = $debug;
		$this->__request();
	}
	/**
	* set log
	* @param array|string $data data
	*
	* @return AliGenie
	*/
	protected function setLog($data)
	{
		$this->debug and file_put_contents('speakers.log',date('Y-m-d H:i:s')."\n".get_called_class()."\n".(is_array($data) ? var_export($data,true) : $data)."\n\n",FILE_APPEND);
		return $this;
	}

	/**
	* get request
	*/
	protected abstract function __request();
	/**
	* get request
	*
	* @return Request
	*/
	public function getRequest()
	{
		return $this->request;
	}

	/**
	* response
	*
	* @param Response $response
	* @param array $params
	*
	* @throws Exception
	*/
	public function response(Response $response,$params = [])
	{
		$output = $response->getResponse($this->request,$params);

		$this->setLog('response')->setLog($output);

		echo json_encode($output,JSON_UNESCAPED_UNICODE);exit;
	}
}
