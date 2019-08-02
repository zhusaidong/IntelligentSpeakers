<?php
/**
 * 天猫精灵 demo
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

require('../vendor/autoload.php');

use Zhusaidong\IntelligentSpeakers\Aligenie\AliGenie;
use Zhusaidong\IntelligentSpeakers\Aligenie\Response;

$aligenie = new AliGenie(AliGenie::getPrivateKeyFromFile('private_key'), FALSE);

$request = $aligenie->getRequest();

$response = new Response();

switch($request->intentName)
{
	case '你好':
		$response->toText('你好');
		break;
}

$aligenie->response($response);
