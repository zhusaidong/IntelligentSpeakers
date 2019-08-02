<?php
/**
 * Request
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace Zhusaidong\IntelligentSpeakers\Xiaoai;

use Zhusaidong\IntelligentSpeakers\Interfaces\RequestInterface;

class Request implements RequestInterface
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
	 * @var array $userInfo user info
	 */
	public $userInfo = NULL;
	/**
	 * @var array $oauthInfo third party application oauth info
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
	 * @var array $intentInfo intentInfo
	 */
	public $intentInfo = NULL;
	/**
	 * @var array $slotInfo slotInfo
	 */
	public $slotInfo = NULL;
	/**
	 * @var array $eventInfo event info
	 */
	public $eventInfo = NULL;
	/**
	 * @var array $noResponse no response
	 */
	public $noResponse = FALSE;
	
	/**
	 * request handle
	 *
	 * @param array $input
	 *
	 * @return mixed
	 */
	public function handle(array $input)
	{
		$this->userInfo  = $input['session'];
		$this->oauthInfo = $input['context'] ?? NULL;
		
		$this->requestInfo = $request = $input['request'];
		$this->requestType = $request['type'];
		$this->requestId   = $request['request_id'];
		
		$this->noResponse = $request['no_response'] ?? FALSE;
		$this->intentInfo = $request['intent'] ?? NULL;
		$this->slotInfo   = $request['slot_info'] ?? NULL;
		
		if(isset($request['event_type']))
		{
			$this->eventInfo = [
				'eventType'     => $request['event_type'],
				'eventProperty' => $request['event_property'],
			];
		}
		
		return;
	}
}
