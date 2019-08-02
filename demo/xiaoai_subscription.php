<?php
/**
 * 小爱订阅号技能 demo
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

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
$feed2 = new Feed([
	'id'          => '2',
	'streamUrl'   => 'streamUrl',
	'pubDate'     => time(),
	'title'       => 'title',
	'description' => 'description',
	'link'        => 'link',
]);

$subscription = new Subscription();
$subscription->addFeed($feed1)->addFeed($feed2)->toJson();
