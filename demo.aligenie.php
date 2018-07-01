<?php
require('./src/autoload.php');

use \speakers\aligenie\AliGenie;
use \speakers\aligenie\Request;
use \speakers\aligenie\Response;

$aligenie = new AliGenie(AliGenie::getPrivateKeyFromFile('private_key'),TRUE);

$request  = $aligenie->getRequest();

$response = new Response();

switch($request->intentName)
{
	case '你好':
		$response->toText('你好');
		break;
}

$aligenie->response($response,['resultType'=>Request::TYPE_RESULT]);
