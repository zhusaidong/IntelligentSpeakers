<?php
/**
* 小爱
* 
* @author zhusaidong [zhusaidong@gmail.com]
*/
namespace speakers\xiaoai;

use Speakers;
use speakers\xiaoai\Request as XiaoAiRequest;

class XiaoAi extends Speakers
{
	/**
	* verification success
	*/
	const VERIFICATION_SUCCESS = TRUE;
	/**
	* verification error
	*/
	const VERIFICATION_ERROR = FALSE;
	
	private $signMethod = 'MIAI-HmacSHA256-V1';
	
	public function __construct($keyId,$secret,$debug = FALSE)
	{
		$this->keyId = $keyId;
		$this->secret = $secret;
		
		$this->request = new XiaoAiRequest;
		
		parent::__construct($debug);
	}
	private function getServer($server_key)
	{
		$debug_server_data_file = 'debug.xiaoai.server.php';
		is_file($debug_server_data_file) and $_SERVER = include($debug_server_data_file);
		
		return isset($_SERVER[$server_key]) ? $_SERVER[$server_key] : '';
	}
	/**
	* generate signature
	* 
	* @return string signature
	*/
	private function generateSignature()
	{
		$servers = ['REQUEST_METHOD','REQUEST_URI','QUERY_STRING','HTTP_X_XIAOMI_DATE','HTTP_HOST','HTTP_CONTENT_TYPE','HTTP_CONTENT_MD5'];
		
		$auths = [];
		foreach($servers as $server)
		{
			$auths[] = $this->getServer($server);
		}
		$auths[] = '';
		
		$auth = implode("\n",$auths);
		
		return $this->signMethod.' '.$this->keyId.'::'.hash_hmac('sha256',$auth,base64_decode($this->secret));
	}
	/**
	* verification signature
	* 
	* @return boolean
	*/
	public function verificationSignature()
	{
		return $this->generateSignature() == $this->getServer('HTTP_AUTHORIZATION');
	}
	/**
	* get request
	* 
	* @return XiaoAi
	*/
	protected function __request()
	{
		$debug_data_file = 'debug.xiaoai.data';
		$_input = file_get_contents(is_file($debug_data_file) ? $debug_data_file : 'php://input');
		
		$input = json_decode($_input,TRUE);
		
		$this->setLog('request')->setLog($input);
		
		$this->request->handle($input);
		
		return $this;
	}
}
