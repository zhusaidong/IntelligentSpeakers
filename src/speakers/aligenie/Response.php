<?php
/**
* Response
*
* @author zhusaidong [zhusaidong@gmail.com]
*/
namespace speakers\aligenie;

use Response as BaseResponse;
use Exception;

class Response extends BaseResponse
{
	/**
	* 代表执行成功
	*/
	const CODE_SUCCESS = 'SUCCESS';
	/**
	* 代表接收到的请求参数出错
	*/
	const CODE_PARAMS_ERROR = 'PARAMS_ERROR';
	/**
	* 代表回复结果生成出错
	*/
	const CODE_EXECUTE_ERROR = 'EXECUTE_ERROR';

	/**
	* @var array $returnValue return value
	*/
	private $returnValue = [];
	/**
	* @var array $askedInfos asked infos
	*/
	private $askedInfos = [];

	/**
	* get response
	*
	* @param string $resultType 回复时的状态标识
	* @param array  $properties 额外信息
	*
	* @return array
	* 
	* @throws Exception
	*/
	public function getResponse($request,$params = [])
	{
		$resultType = isset($params['resultType']) ? $params['resultType'] : Request::TYPE_RESULT;
		if($resultType == Request::TYPE_ASK_INF and empty($this->askedInfos))
		{
			throw new Exception('在ASK_INF状态下,必须设置本次追问的具体参数名(开发者平台意图参数下配置的参数信息)');
		}

		$data = [
			'returnCode'			=>0,	//"0"默认表示成功，其他不成功的字段自己可以确定
			'returnErrorSolution'	=>'',	//出错时解决办法的描述信息
			'returnMessage'			=>'',	//返回执行成功的描述信息
			'returnValue'=>[
				'resultType'	=>$resultType,
				'properties'	=>isset($params['properties']) ? $params['properties'] : [],
				'askedInfos'	=>$resultType != Request::TYPE_ASK_INF ? [] : $this->askedInfos,
				'executeCode'	=>self::CODE_SUCCESS,
			],
		];
		$data['returnValue'] += $this->returnValue;

		return $data;
	}
	/**
	* AskedInfoMsg
	*
	* @param string $parameterName 询问的参数名(非实体名)
	* @param int $intentId 意图ID
	*
	* @return Response
	*/
	public function setAskedInfos($parameterName,$intentId)
	{
		$this->askedInfos[] = [
			'parameterName'	=>$parameterName,
			'intentId'		=>$intentId,
		];
		return $this;
	}
	/**
	* text
	*
	* @param string $msg msg
	*
	* @return Response
	*/
	public function toText($msg)
	{
		$this->returnValue = [
			'reply'	 =>$msg,//回复播报语句
			'actions'=>[],
		];
		return $this;
	}
	/**
	* audio
	*
	* @param int $audioId audio id
	*
	* @return Response
	*/
	public function toAudio($audioId)
	{
		$this->returnValue['reply'] = '';
		$this->returnValue['actions'][] = [
			'name'		=>'audioPlayGenieSource',
			'properties'=>[
				'audioGenieId'=>$audioId
			],
		];
		return $this;
	}
}
