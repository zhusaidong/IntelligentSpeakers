<?php
/**
* demo
* @author zhusaidong [zhusaidong@gmail.com]
*/
require('XiaoAi.php');

$xiaoai = new XiaoAi('keyid','secret',TRUE);

$request = $xiaoai->getXiaoAiRequest();

$respose = new XiaoAiResponse();

if($xiaoai->verificationSignature() === XiaoAi::VERIFICATION_SUCCESS)
{
	switch($request->requestType)
	{
		case XiaoAiRequest::NO_REQUEST:
			$respose->toSpeak('error');
			break;
		case XiaoAiRequest::TYPE_START:
			$respose
				->toDirectivesTTS('欢迎使用')
				->toDirectivesTTS('开始录音')
				->registerActions(XiaoAiResponse::ACTION_LEAVE_MSG);
			break;
		case XiaoAiRequest::TYPE_INTENT:
			if($request->noResponse)
			{
				$respose
					->toSpeak('请再说一次吧')
					->registerActions(XiaoAiResponse::ACTION_LEAVE_MSG);
			}
			else
			{
				//$query = $request->intentInfo['query'];
				if(($eventInfo = $request->eventInfo) !== NULL)
				{
					switch($eventInfo['eventType'])
					{
						case XiaoAiResponse::EVENT_LEAVEMSG_FINISHED:
							$msg_file_id = $eventInfo['eventProperty']['msg_file_id'];
							
							$respose
								->toSpeak('开始播放录音')
								->registerActions(XiaoAiResponse::ACTION_PLAY_MSG,['file_id_list'=>[$msg_file_id]])
								->registerEvents(XiaoAiResponse::EVENT_MEDIAPLAYER);
							break;
						default:
							$respose
								->registerEvents(XiaoAiResponse::EVENT_MEDIAPLAYER)
								->toDirectivesAudio('https://openapi.zhusaidong.cn/xiaoai/ss.mp3');
							break;
					}
				}
				else
				{
					$respose->toSpeak('录音失败');
				}
			}
			break;
		case XiaoAiRequest::TYPE_END:
			$respose->toSpeak('感谢您的使用');
			break;
	}
}
else
{
	$respose->toSpeak('校验失败');
}
$xiaoai->response($respose,$request->requestType == XiaoAiRequest::TYPE_END);
