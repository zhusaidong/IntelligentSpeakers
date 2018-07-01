Welcome to the AliGenie IntelligentSpeakers Doc!

[官方文档](http://doc-bot.tmall.com/docs/doc.htm?spm=0.0.0.0.ftGHTP&treeId=393&articleId=106952&docType=1)

---

- ### 使用流程

	- 初始化 api

	```php
	require('./src/autoload.php');

	use \speakers\aligenie\AliGenie;
	use \speakers\aligenie\Request;
	use \speakers\aligenie\Response;

	$aligenie = new AliGenie(AliGenie::getPrivateKeyFromFile('private_key'),TRUE);
	```
	
	> 私钥,公钥可在 [这里](http://web.chacuo.net/netrsakeypair) 获取
	
	- 加载私钥

	```php
	AliGenie::getPrivateKeyFromFile('private_key');
	```

	- 获取请求对象

	```php
	$request = $aligenie->getRequest();
	```

	- 创建回复对象

	```php
	$response = new Response();
	```

	- 设置回复消息

	```php
	$response->toText('msg');
	```

	- 发送回复

	```php
	$aligenie->response($response);
	```

- ### 常量说明

	- 请求类型

	```php
	Request::TYPE_ASK_INF
		ask information
		
	Request::TYPE_RESULT
		return result
		
	Request::TYPE_CONFIRM
		need confirm
	```
	
	- 回复事件
	
	```php
	Response::CODE_SUCCESS
		执行成功
	
	Response::CODE_PARAMS_ERROR
		接收到的请求参数出错
	
	Response::CODE_EXECUTE_ERROR
		回复结果生成出错
	```
	
- ### 对象说明
	
	- Request对象

		```php
		public $sessionId 	= NULL;
			session id
		
		public $utterance 	= NULL;
			user input msg
		
		public $skillName 	= NULL;
			skill name
		
		public $intentName 	= NULL;
			intent name
		
		public $token 		= NULL;
			技能鉴权token
		
		public $requestData = NULL;
			extra information
		
		public $slotEntities= NULL;
			slot information
		
		public $slots = [];
			slots
		```

	- Response对象

		```php
		setAskedInfos($parameterName,$intentId)
			AskedInfoMsg
		
		toText($msg)
			回复播报语句
		
		toAudio($audioId)
			回复audio id
		```
