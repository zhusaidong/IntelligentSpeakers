<?php
/**
 * 天猫精灵
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */
namespace Zhusaidong\IntelligentSpeakers\Aligenie;

use Zhusaidong\IntelligentSpeakers\Speakers;
use Zhusaidong\IntelligentSpeakers\Aligenie\Request as AliGenieRequest;
use Exception;

class AliGenie extends Speakers
{
	private $privateKey = NULL;
	
	/**
	 * AliGenie constructor.
	 *
	 * @param null $privateKey
	 * @param bool $debug
	 */
	public function __construct($privateKey = NULL, $debug = FALSE)
	{
		$this->privateKey = $privateKey;
		
		$this->request = new AliGenieRequest;
		
		parent::__construct($debug);
	}
	
	/**
	 * get private key from file
	 *
	 * @param string $privateKeyPath private key path
	 *
	 * @return string private key
	 */
	public static function getPrivateKeyFromFile(string $privateKeyPath) : string
	{
		return is_file($privateKeyPath) ? file_get_contents($privateKeyPath) : '';
	}
	
	/**
	 * privateKey decrypt
	 *
	 * @param string $data        data
	 * @param string $private_key private key
	 *
	 * @return string decrypted data
	 */
	private function privateKeyDecrypt($data, $private_key)
	{
		$decrypted = '';
		$pi_key    = openssl_pkey_get_private($private_key);
		$plainData = str_split(base64_decode($data), 128);
		foreach($plainData as $chunk)
		{
			$str = '';
			if(@openssl_private_decrypt($chunk, $str, $pi_key) === FALSE)
			{
				return FALSE;
			}
			$decrypted .= $str;
		}
		
		return $decrypted;
	}
	
	/**
	 * get request
	 *
	 * @return AliGenie
	 * @throws Exception
	 */
	protected function __request()
	{
		if(($input = json_decode(file_get_contents('php://input'), TRUE)) !== NULL)
		{
			//需要解密
			if($this->privateKey != NULL)
			{
				if(($deInput = $this->privateKeyDecrypt($input['securityQuery'], $this->privateKey)) === FALSE)
				{
					throw new Exception('解密失败');
				}
				$input = json_decode($deInput, TRUE);
			}
			
			$this->setLog('request')->setLog($input);
			$this->request->handle($input);
		}
		
		return $this;
	}
}
