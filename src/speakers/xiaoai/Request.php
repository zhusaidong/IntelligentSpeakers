<?php
/**
* Request
* 
* @author zhusaidong [zhusaidong@gmail.com]
*/
namespace speakers\xiaoai;

use Request as BaseRequest;

class Request extends BaseRequest
{
	const NO_REQUEST = -1;
	/**
	* skill request start
	*/
	const TYPE_START = 0;
	/**
	* skill request in intent
	*/
	const TYPE_INTENT = 1;
	/**
	* skill request end
	*/
	const TYPE_END = 2;
	
	/**
	* @var $userInfo user info
	*/
	public $userInfo = NULL;
	/**
	* @var $oauthInfo third party application oauth info
	*/
	public $oauthInfo = NULL;
	/**
	* @var $requestInfo request info
	*/
	public $requestInfo = NULL;
	/**
	* @var $requestType request type
	*/
	public $requestType = NULL;
	/**
	* @var $requestId request id
	*/
	public $requestId = NULL;
	/**
	* @var $intentInfo intentInfo
	*/
	public $intentInfo = NULL;
	/**
	* @var $slotInfo slotInfo
	*/
	public $slotInfo = NULL;
	/**
	* @var $eventInfo event info
	*/
	public $eventInfo = NULL;
	/**
	* @var $noResponse no response
	*/
	public $noResponse = FALSE;
	
	/**
	* handle
	* 
	* @param array $input
	*/
	public function handle($input)
	{
		if($input === NULL)
		{
			$this->requestType = -1;
			return;
		}
		$this->userInfo 	= $input['session'];
		$this->oauthInfo 	= isset($input['context']) ? $input['context'] : NULL;
		
		$this->requestInfo 	= $request = $input['request'];
		$this->requestType  = $request['type'];
		$this->requestId 	= $request['request_id'];
		
		$this->noResponse 	= isset($request['no_response']) ? $request['no_response'] : FALSE;
		$this->intentInfo	= isset($request['intent']) ? $request['intent'] : NULL;
		$this->slotInfo 	= isset($request['slot_info']) ? $request['slot_info'] : NULL;
		
		if(isset($request['event_type']))
		{
			$this->eventInfo = [
				'eventType' 	=> $request['event_type'],
				'eventProperty'	=> $request['event_property'],
			];
		}
	}
}
