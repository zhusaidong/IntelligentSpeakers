<?php

/**
 * Speakers
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace IntelligentSpeakers;

abstract class Speakers
{
	protected $request = NULL;
	protected $debug   = FALSE;
	
	/**
	 * Speakers constructor.
	 *
	 * @param bool $debug
	 */
	public function __construct(bool $debug = FALSE)
	{
		$this->debug = $debug;
		$this->__request();
	}
	
	/**
	 * set log
	 *
	 * @param array|string $data data
	 *
	 * @return Speakers
	 */
	protected function setLog($data) : Speakers
	{
		$data = is_array($data) ? var_export($data, TRUE) : $data;
		$msg  = date('Y-m-d H:i:s') . PHP_EOL . get_called_class() . PHP_EOL . $data . PHP_EOL . PHP_EOL;
		$this->debug and file_put_contents('speakers.log', $msg, FILE_APPEND);
		
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
	public function getRequest() : Request
	{
		return $this->request;
	}
	
	/**
	 * response
	 *
	 * @param Response $response
	 * @param array    $params
	 */
	public function response(Response $response, array $params = [])
	{
		$output = $response->getResponse($this->request, $params);
		
		$this->setLog('response')->setLog($output);
		
		echo json_encode($output, JSON_UNESCAPED_UNICODE);
		exit;
	}
}
