<?php
/**
 * Response
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace Zhusaidong\IntelligentSpeakers\Aligenie;

use Zhusaidong\IntelligentSpeakers\Interfaces\RequestInterface;
use Zhusaidong\IntelligentSpeakers\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
	/**
	 * 执行成功
	 */
	private const CODE_SUCCESS = 'SUCCESS';
	/**
	 * 接收到的请求参数出错
	 */
	private const CODE_PARAMS_ERROR = 'PARAMS_ERROR';
	/**
	 * 自身代码有异常
	 */
	private const CODE_EXECUTE_ERROR = 'EXECUTE_ERROR';
	/**
	 * 回复结果生成出错
	 */
	private const CODE_REPLY_ERROR = 'REPLY_ERROR';
	
	/**
	 * ask information
	 */
	private const TYPE_ASK_INF = 'ASK_INF';
	/**
	 * return result
	 */
	private const TYPE_RESULT = 'RESULT';
	/**
	 * need confirm
	 */
	private const TYPE_CONFIRM = 'CONFIRM';
	
	/**
	 * @var string $resultType result type
	 */
	private $resultType = self::TYPE_RESULT;
	/**
	 * @var array $returnValue return value
	 */
	private $returnValue = [];
	/**
	 * @var array $askedInfos asked infos
	 */
	private $askedInfos = [];
	
	/**
	 * AskedInfoMsg
	 *
	 * @param string $parameterName 询问的参数名(非实体名)
	 * @param int    $intentId      意图ID
	 *
	 * @return Response
	 */
	public function setAskedInfos($parameterName, $intentId) : Response
	{
		$this->resultType   = self::TYPE_ASK_INF;
		$this->askedInfos[] = [
			'parameterName' => $parameterName,
			'intentId'      => $intentId,
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
	public function toText($msg) : Response
	{
		$this->returnValue = [
			'reply'   => $msg,//回复播报语句
			'actions' => [],
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
	public function toAudio($audioId) : Response
	{
		$this->returnValue['reply']     = '';
		$this->returnValue['actions'][] = [
			'name'       => 'audioPlayGenieSource',
			'properties' => [
				'audioGenieId' => $audioId,
			],
		];
		
		return $this;
	}
	
	/**
	 * is confirm
	 *
	 * @return Response
	 */
	public function isConfirm() : Response
	{
		$this->resultType = self::TYPE_CONFIRM;
		
		return $this;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getResponse(RequestInterface $request, array $params = []) : array
	{
		$data = [
			'returnCode'  => 0,
			'returnValue' => [
				'resultType'  => $this->resultType,
				'properties'  => $params,
				'askedInfos'  => $this->resultType != self::TYPE_ASK_INF ? [] : $this->askedInfos,
				'executeCode' => self::CODE_SUCCESS,
			],
		];
		
		$data['returnValue'] += $this->returnValue;
		
		return $data;
	}
}
