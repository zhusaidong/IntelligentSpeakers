<?php
/**
* XiaoAi
* @author zhusaidong [zhusaidong@gmail.com]
*/
class XiaoAi
{
	/**
	* verification success
	*/
	const VERIFICATION_SUCCESS = TRUE;
	/**
	* verification error
	*/
	const VERIFICATION_ERROR = FALSE;	
	
	/**
	* @var $signMethod signature method
	*/
	private $signMethod = 'MIAI-HmacSHA256-V1';
	/**
	* @var $keyId keyId
	*/
	private $keyId = NULL;
	/**
	* @var $secret secret
	*/
	private $secret = NULL;
	/**
	* @var $request XiaoAiRequest
	*/
	private $request = NULL;
	
	/**
	* __construct
	* 
	* @param string $keyId
	* @param string $secret
	* @param boolean $debug
	*/
	public function __construct($keyId,$secret,$debug = FALSE)
	{
		$this->keyId = $keyId;
		$this->secret = $secret;
		$this->debug = $debug;
		
		$this->request = new XiaoAiRequest();
		
		$this->getRequest();
	}
	/**
	* 日志
	* 
	* @param string|array $data
	* 
	* @return XiaoAi
	*/
	private function setLog($data)
	{
		file_put_contents('XiaoAi.log',date('Y-m-d H:i:s')."\n".(is_array($data) ? var_export($data,true) : $data)."\n\n",FILE_APPEND);
		return $this;
	}
	/**
	* generate signature
	* 
	* @return string signature
	*/
	private function generateSignature()
	{
		$auth = [
			$_SERVER['REQUEST_METHOD'],
			$_SERVER['REQUEST_URI'],
			$_SERVER['QUERY_STRING'],
			$_SERVER['HTTP_X_XIAOMI_DATE'],
			$_SERVER['HTTP_HOST'],
			$_SERVER['HTTP_CONTENT_TYPE'],
			$_SERVER['HTTP_CONTENT_MD5'],
			'',
		];
		$auth = implode("\n",$auth);
		return $this->signMethod.' '.$this->keyId.'::'.hash_hmac('sha256',$auth,base64_decode($this->secret));
	}
	/**
	* verification signature
	* 
	* @return boolean
	*/
	public function verificationSignature()
	{
		return $this->generateSignature() == $_SERVER['HTTP_AUTHORIZATION'];
	}
	/**
	* get request
	* 
	* @return XiaoAi
	*/
	private function getRequest()
	{
		$input = json_decode(file_get_contents('php://input'),TRUE);
		
		$this->debug and $this->setLog('request')->setLog($input);
		
		$this->request->handle($input);
		
		return $this;
	}
	/**
	* get request
	* 
	* @return XiaoAiRequest $request
	*/
	public function getXiaoAiRequest()
	{
		return $this->request;
	}
	/**
	* response
	* 
	* @param XiaoAiResponse $response
	* @param boolean $exit
	*/
	public function response(XiaoAiResponse $response,$exit = FALSE)
	{
		$output = $response->getResponse($exit);
		$output['not_understand'] = $this->request->noResponse;
		
		$this->debug and $this->setLog('response')->setLog($output);
		
		echo json_encode($output,JSON_UNESCAPED_UNICODE);exit;
	}
	
}

/**
* XiaoAi Request
*/
class XiaoAiRequest
{
	const NO_REQUEST = -1;
	
	//0：技能进入请求； 1：技能进行中请求;2：请求结束请求
	const TYPE_START = 0;
	const TYPE_INTENT = 1;
	const TYPE_END = 2;
	
	public $userInfo = NULL;
	public $oauthInfo = NULL;//第三方应用授权信息
	
	public $requestInfo = NULL;
	
	public $requestType = NULL;
	public $requestId = NULL;
	
	public $intentInfo = NULL;//未识别到时的意图
	public $slotInfo = NULL;//识别到时的意图
	
	public $eventInfo = NULL;//事件
	
	public $noResponse = FALSE;//未收到回复
	
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

/**
* XiaoAi Response
*/
class XiaoAiResponse
{
	/**
	* EVENT MEDIAPLAYER
	* 	小爱音箱播放器即将播放结束，该通常用来加载更多的资源
	*/
	const EVENT_MEDIAPLAYER 		= 'mediaplayer.playbacknearlyfinished';
	/**
	* EVENT LEAVEMSG_FINISHED
	* 	假如技能需要录音，则该事件表示录音结束，通常会伴随有fileid给到开发者
	*/
	const EVENT_LEAVEMSG_FINISHED 	= 'leavemsg.finished';
	/**
	* EVENT LEAVEMSG_FAILED
	* 	表示录音失败
	*/
	const EVENT_LEAVEMSG_FAILED 	= 'leavemsg.failed';
	
	/**
	* ACTION LEAVE_MSG
	* 	指导小爱智能设备开始录音
	*/
	const ACTION_LEAVE_MSG 	= 'leave_msg';
	/**
	* ACTION PLAY_MSG
	* 	指导小爱智能设备开始播放录音
	*/
	const ACTION_PLAY_MSG 	= 'play_msg';
	
	/**
	* @var $registerActions register actions
	*/
	private $registerActions = [];
	/**
	* @var $registerEvents register events
	*/
	private $registerEvents = [];
	/**
	* @var $directives directives
	*/
	private $directives 	= [];
	/**
	* @var $toSpeak toSpeak
	*/
	private $toSpeak 		= [];
	
	/**
	* get response
	* 
	* @param boolean $exit
	* 
	* @return array
	*/
	public function getResponse($exit = FALSE)
	{
		$response = [
			'version'		=>'1.0',
			'is_session_end'=>!!$exit,
			'response'		=>[
				'open_mic' =>!$exit,
			],
		];
		
		if(!empty($this->registerActions))
		{
			$response['response'] += $this->registerActions;
		}
		
		if(!empty($this->directives))
		{
			$response['response'] += $this->directives;
			$response['response']['open_mic'] = FALSE;//放音频时无法继续开麦
			
			//该场景只能和directives联合使用
			if(!empty($this->registerEvents))
			{
				$response['response'] += $this->registerEvents;
			}
		}
		else if(!empty($this->toSpeak))
		{
			$response['response'] += $this->toSpeak;
		}
		else
		{
			throw new \Exception('no response msg!');
		}
		
		return $response;
	}
	/**
	* register actions
	* 
	* @param string $action_name action name
	* @param array $actionProperty action property
	* 
	* @return XiaoAiResponse
	*/
	public function registerActions($action_name,$actionProperty = NULL)
	{
		$this->registerActions['action'] = $action_name;
		
		if($actionProperty != NULL)
		{
			$this->registerActions['action_property'] = $actionProperty;
		}
		
		return $this;
	}
	/**
	* register events
	* 
	* @param string $event_name event name
	* 
	* @return XiaoAiResponse
	*/
	public function registerEvents($event_name)
	{
		$this->registerEvents['register_events'][] = ['event_name'=>$event_name];
		return $this;
	}
	/**
	* simple text
	* 
	* @param string $msg
	* 
	* @return XiaoAiResponse
	*/
	public function toSpeak($msg)
	{
		$this->toSpeak['to_speak'] = [
			'type'=>0,
			'text'=>$msg,
		];
		return $this;
	}
	/**
	* complex operation-audio
	* 
	* @param string $audio 音频url
	* @param string $token
	* 
	* @return XiaoAiResponse
	*/
	public function toDirectivesAudio($audio,$token = '')
	{
		$this->directives['directives'][] = [
			'type'	=>'audio',
			'audio_item'=>[
				'stream'=>[
					'url'	=>$audio,
					'token'	=>$token,
					'offset_in_milliseconds'=>0,
				],
			],
		];
		return $this;
	}
	/**
	* complex operation-multiple tts
	* 
	* @param string $msg
	* 
	* @return XiaoAiResponse
	*/
	public function toDirectivesTTS($msg)
	{
		$this->directives['directives'][] = [
			'type'		=>'tts',
			'tts_item'	=>[
				'type'=>'text',
				'text'=>$msg,
			],
		];
		return $this;
	}
}
