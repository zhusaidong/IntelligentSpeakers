<?php
/**
 * Response
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace Zhusaidong\IntelligentSpeakers\Xiaoai;

use Zhusaidong\IntelligentSpeakers\Interfaces\RequestInterface;
use Zhusaidong\IntelligentSpeakers\Interfaces\ResponseInterface;
use Exception;

class Response implements ResponseInterface
{
	/**
	 * EVENT MEDIAPLAYER
	 *    小爱音箱播放器即将播放结束，该通常用来加载更多的资源
	 */
	const EVENT_MEDIAPLAYER = 'mediaplayer.playbacknearlyfinished';
	/**
	 * EVENT LEAVEMSG_FINISHED
	 *    假如技能需要录音，则该事件表示录音结束，通常会伴随有fileid给到开发者
	 */
	const EVENT_LEAVEMSG_FINISHED = 'leavemsg.finished';
	/**
	 * EVENT LEAVEMSG_FAILED
	 *    表示录音失败
	 */
	const EVENT_LEAVEMSG_FAILED = 'leavemsg.failed';
	/**
	 * ACTION LEAVE_MSG
	 *    指导小爱智能设备开始录音
	 */
	const ACTION_LEAVE_MSG = 'leave_msg';
	/**
	 * ACTION PLAY_MSG
	 *    指导小爱智能设备开始播放录音
	 */
	const ACTION_PLAY_MSG = 'play_msg';
	/**
	 * @var array $registerActions register actions
	 */
	private $registerActions = [];
	/**
	 * @var array $registerEvents register events
	 */
	private $registerEvents = [];
	/**
	 * @var array $directives directives
	 */
	private $directives = [];
	/**
	 * @var array $toSpeak toSpeak
	 */
	private $toSpeak = [];
	
	/**
	 * register actions
	 *
	 * @param string $actionName     action name
	 * @param array  $actionProperty action property
	 *
	 * @return Response
	 */
	public function registerActions($actionName, $actionProperty = NULL) : Response
	{
		$this->registerActions['action'] = $actionName;
		
		if($actionProperty != NULL)
		{
			$this->registerActions['action_property'] = $actionProperty;
		}
		
		return $this;
	}
	
	/**
	 * register events
	 *
	 * @param string $eventName event name
	 *
	 * @return Response
	 */
	public function registerEvents($eventName) : Response
	{
		$this->registerEvents['register_events'][] = ['event_name' => $eventName];
		
		return $this;
	}
	
	/**
	 * simple text
	 *
	 * @param string $msg
	 *
	 * @return Response
	 */
	public function toSpeak($msg) : Response
	{
		$this->toSpeak['to_speak'] = [
			'type' => 0,
			'text' => $msg,
		];
		
		return $this;
	}
	
	/**
	 * complex operation-audio
	 *
	 * @param string $audio 音频url
	 * @param string $token
	 *
	 * @return Response
	 */
	public function toDirectivesAudio($audio, $token = '') : Response
	{
		$this->directives['directives'][] = [
			'type'       => 'audio',
			'audio_item' => [
				'stream' => [
					'url'                    => $audio,
					'token'                  => $token,
					'offset_in_milliseconds' => 0,
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
	 * @return Response
	 */
	public function toDirectivesTTS($msg) : Response
	{
		$this->directives['directives'][] = [
			'type'     => 'tts',
			'tts_item' => [
				'type' => 'text',
				'text' => $msg,
			],
		];
		
		return $this;
	}
	
	/**
	 * @inheritDoc
	 * @throws Exception
	 */
	public function getResponse(RequestInterface $request, array $params = []) : array
	{
		$exit = $request->requestType == Request::TYPE_END;
		
		$response = [
			'version'        => '1.0',
			'is_session_end' => !!$exit,
			'response'       => [
				'open_mic' => !$exit,
			],
			'not_understand' => $request->noResponse,
		];
		
		if(!empty($this->registerActions))
		{
			$response['response'] += $this->registerActions;
		}
		
		if(!empty($this->directives))
		{
			$response['response']             += $this->directives;
			$response['response']['open_mic'] = FALSE;//放音频时无法继续开麦
			
			//该场景只能和directives联合使用
			if(!empty($this->registerEvents))
			{
				$response['response'] += $this->registerEvents;
			}
		}
		elseif(!empty($this->toSpeak))
		{
			$response['response'] += $this->toSpeak;
		}
		else
		{
			throw new Exception('no response msg!');
		}
		
		return $response;
	}
}
