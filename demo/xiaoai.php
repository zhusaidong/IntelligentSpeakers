<?php
/**
 * 小爱 demo
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

require('../vendor/autoload.php');

use Zhusaidong\IntelligentSpeakers\Xiaoai\XiaoAi;
use Zhusaidong\IntelligentSpeakers\Xiaoai\Request;
use Zhusaidong\IntelligentSpeakers\Xiaoai\Response;

$xiaoai = new XiaoAi('keyid', 'secret', FALSE);

$request = $xiaoai->getRequest();

$respose = new Response;

if($xiaoai->verificationSignature() === XiaoAi::VERIFICATION_SUCCESS)
{
	switch($request->requestType)
	{
		case Request::NO_REQUEST:
			$respose->toSpeak('error');
			break;
		case Request::TYPE_START:
			$respose->toDirectivesTTS('欢迎使用')->toDirectivesTTS('开始录音')->registerActions(Response::ACTION_LEAVE_MSG);
			break;
		case Request::TYPE_INTENT:
			if($request->noResponse)
			{
				$respose->toSpeak('请再说一次吧')->registerActions(Response::ACTION_LEAVE_MSG);
			}
			else
			{
				//$query = $request->intentInfo['query'];
				if(($eventInfo = $request->eventInfo) !== NULL)
				{
					switch($eventInfo['eventType'])
					{
						case Response::EVENT_LEAVEMSG_FINISHED:
							$msg_file_id = $eventInfo['eventProperty']['msg_file_id'];
							
							$respose->toSpeak('开始播放录音')
								->registerActions(Response::ACTION_PLAY_MSG, ['file_id_list' => [$msg_file_id]])
								->registerEvents(Response::EVENT_MEDIAPLAYER);
							break;
						default:
							$respose->registerEvents(Response::EVENT_MEDIAPLAYER)->toDirectivesAudio('audio url');
							break;
					}
				}
				else
				{
					$respose->toSpeak('录音失败');
				}
			}
			break;
		case Request::TYPE_END:
			$respose->toSpeak('感谢您的使用');
			break;
	}
}
else
{
	$respose->toSpeak('校验失败');
}
$xiaoai->response($respose);
