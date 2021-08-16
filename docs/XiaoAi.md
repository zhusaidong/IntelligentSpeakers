Welcome to the XiaoAi IntelligentSpeakers Doc!

[官方文档](https://xiaoai.mi.com/documents/Home?type=/api/doc/render_markdown/SkillAccess/SkillDocument/CustomSkills/CustomSkillsMain)

---

- ### 使用流程

	- 初始化 api

	```php
	require('../vendor/autoload.php');
    
    use Zhusaidong\IntelligentSpeakers\Xiaoai\XiaoAi;
    use Zhusaidong\IntelligentSpeakers\Xiaoai\Request;
    use Zhusaidong\IntelligentSpeakers\Xiaoai\Response;
    
    $xiaoai = new XiaoAi('keyid', 'secret', TRUE);
	```
	
	> `key_id`,`secret` 可在 [这里](https://developers.xiaoai.mi.com/skills/create/list) 获取

	- 校验消息

	```php
	$xiaoai->verificationSignature();
	```

	- 获取请求对象

	```php
	$request = $xiaoai->getRequest();
	```

	- 创建回复对象

	```php
	$response = new Response();
	```

	- 设置回复消息

	```php
	$response->toSpeak('msg');
	```

	- 发送回复

	```php
	$xiaoai->response($response);
	```

- ### 常量说明

	- 请求类型

	```php
	Request::TYPE_START	
		技能进入请求

	Request::TYPE_INTENT
		技能进行中请求

	Request::TYPE_END
		技能结束请求
	```
	
	- 回复事件

	```php
	Response::EVENT_MEDIAPLAYER
		小爱音箱播放器即将播放结束，该通常用来加载更多的资源

	Response::EVENT_LEAVEMSG_FINISHED
		假如技能需要录音，则该事件表示录音结束，通常会伴随有`fileid`给到开发者

	Response::EVENT_LEAVEMSG_FAILED
		表示录音失败
	```

	- 回复动作

	```php
	Response::ACTION_LEAVE_MSG
		指导小爱智能设备开始录音

	Response::ACTION_PLAY_MSG
		指导小爱智能设备开始播放录音
	```
	
- ### 对象说明
	
	- Request对象

		```php
		public $userInfo = NULL;
			用户信息
		
		public $oauthInfo = NULL;
			第三方授权信息
		
		public $requestInfo = NULL;
			请求信息
		
		public $requestType = NULL;
			请求类型
		
		public $requestId = NULL;
			请求id
		
		public $intentInfo = NULL;
			原始意图信息-用户发送的原始信息
		
		public $slotInfo = NULL;
			识别意图信息-匹配到意图后的信息
		
		public $eventInfo = NULL;
			事件信息
		
		public $noResponse = FALSE;
			用户是否发送空回复，即设备唤醒后无人应答
		```

	- Response对象

		```php
		toSpeak($msg)
			简单文本

		toDirectivesTTS($msg)
			复杂文本，如多句话

		toDirectivesAudio($audio,$token = '')
			播放音频
		
		registerActions($actionName,$actionProperty = NULL)
			注册动作
		
		registerEvents($eventName)
			注册事件
		```

- ### 订阅号技能
  
    ```php
    require('../vendor/autoload.php');
    
    use Zhusaidong\IntelligentSpeakers\Xiaoai\Subscription;
    use Zhusaidong\IntelligentSpeakers\Xiaoai\Feed;
    
    $feed1 = new Feed([
    	'id'          => '1',
    	'streamUrl'   => 'streamUrl',
    	'pubDate'     => time() - 1,
    	'title'       => 'title',
    	'description' => 'description',
    	'link'        => 'link',
    ]);
    
    $subscription = new Subscription();
    $subscription->addFeed($feed1)->toJson();
    ```
