Welcome to the AliGenie IntelligentSpeakers Doc!

[官方文档](http://doc-bot.tmall.com/docs/doc.htm?spm=0.0.0.0.ftGHTP&treeId=393&articleId=106952&docType=1)

---

- ### 使用流程

	- 初始化 api

	```php
    require('../vendor/autoload.php');
    
    use Zhusaidong\IntelligentSpeakers\Aligenie\AliGenie;
    use Zhusaidong\IntelligentSpeakers\Aligenie\Request;
    use Zhusaidong\IntelligentSpeakers\Aligenie\Response;
    
    $aligenie = new AliGenie(AliGenie::getPrivateKeyFromFile('private_key'), FALSE);
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
  
        isConfirm()
            确认回复
		```
