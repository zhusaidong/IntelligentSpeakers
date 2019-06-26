<?php
require('./vendor/autoload.php');

use IntelligentSpeakers\speakers\aligenie\AliGenie;
use IntelligentSpeakers\speakers\aligenie\Request;
use IntelligentSpeakers\speakers\aligenie\Response;

$aligenie = new AliGenie(AliGenie::getPrivateKeyFromFile('private_key'), FALSE);

$request = $aligenie->getRequest();

$response = new Response();

switch($request->intentName)
{
	case '你好':
		$response->toText('你好');
		break;
}

$aligenie->response($response, ['resultType' => Request::TYPE_RESULT]);
