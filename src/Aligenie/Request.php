<?php
/**
 * Request
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace Zhusaidong\IntelligentSpeakers\Aligenie;

use Zhusaidong\IntelligentSpeakers\Interfaces\RequestInterface;

class Request implements RequestInterface
{
	/**
	 * @var mixed $sessionId session id
	 */
	public $sessionId = NULL;
	/**
	 * @var mixed $utterance user input msg
	 */
	public $utterance = NULL;
	/**
	 * @var mixed $skillName skill name
	 */
	public $skillName = NULL;
	/**
	 * @var mixed $intentName skill name
	 */
	public $intentName = NULL;
	/**
	 * @var mixed $token 技能鉴权token
	 */
	public $token = NULL;
	/**
	 * @var mixed $requestData extra information
	 */
	public $requestData = NULL;
	/**
	 * @var mixed $slotEntities slot information
	 */
	public $slotEntities = NULL;
	/**
	 * @var mixed $slots slots
	 */
	public $slots = [];
	
	/**
	 * request handle
	 *
	 * @param array $input
	 *
	 * @return mixed
	 */
	public function handle(array $input)
	{
		$this->sessionId    = $input['sessionId'];
		$this->utterance    = $input['utterance'];
		$this->skillName    = $input['skillName'];
		$this->intentName   = $input['intentName'];
		$this->requestData  = $input['requestData'];
		$this->slotEntities = $input['slotEntities'];
		
		isset($input['token']) and $this->token = $input['token'];
		
		$this->slots = [];
		if(!empty($this->slotEntities))
		{
			foreach($this->slotEntities as $slotEntity)
			{
				$this->slots[] = [
					'intentName'    => $slotEntity['intentParameterName'],
					'liveTime'      => $slotEntity['liveTime'],         //slot live time
					'originalValue' => $slotEntity['originalValue'],    //original value
					'standardValue' => $slotEntity['standardValue'],    //standard value
				];
			}
		}
		
		return;
	}
}
