<?php
/**
 * 订阅号技能
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\IntelligentSpeakers\Xiaoai;

use DOMDocument;

class Subscription
{
	/**
	 * @var array<Feed> $feeds
	 */
	private $feeds = [];
	
	/**
	 * add feed
	 *
	 * @param Feed $feed
	 *
	 * @return Subscription
	 */
	public function addFeed(Feed $feed) : Subscription
	{
		$this->feeds[] = $feed;
		
		return $this;
	}
	
	/**
	 * get feeds
	 *
	 * @return array
	 */
	private function getFeeds()
	{
		$feeds = [];
		/**
		 * @var Feed $feed
		 */
		foreach($this->feeds as $feed)
		{
			$feeds[] = [
				'id'          => $feed->get('id'),
				'pubDate'     => $feed->get('pubDate'),
				'title'       => $feed->get('title'),
				'streamUrl'   => $feed->get('streamUrl'),
				'description' => $feed->get('description'),
				'link'        => $feed->get('link'),
			];
		}
		
		//时效性限制：7天以内更新的数据
		$feeds = array_filter($feeds, function($v)
		{
			return strtotime($v['pubDate']) >= strtotime('-7 day', time());
		});
		
		//时间排序要求：按照更新时间由新至旧排序
		usort($feeds, function($a, $b)
		{
			return $a['pubDate'] <= $b['pubDate'];
		});
		
		return $feeds;
	}
	
	/**
	 * to json
	 *
	 * @param bool $isReturn
	 *
	 * @return false|string
	 */
	public function toJson($isReturn = FALSE)
	{
		header('Content-Type:application/json; charset=utf-8');
		
		$res = json_encode($this->getFeeds(), JSON_UNESCAPED_UNICODE);
		if($isReturn)
		{
			return $res;
		}
		else
		{
			exit($res);
		}
	}
	
	/**
	 * to rss
	 *
	 * @param bool $isReturn
	 *
	 * @return string
	 */
	public function toRss($isReturn = FALSE)
	{
		header('Content-Type: application/rss+xml');
		
		$xml = new DOMDocument('1.0', 'utf-8');
		
		$channel = $xml->createElement('channel');
		
		$ttl            = $xml->createElement('ttl');
		$ttl->nodeValue = 30;
		
		$channel->appendChild($ttl);
		
		foreach($this->getFeeds() as $feed)
		{
			$item = $xml->createElement('item');
			foreach($feed as $key => $value)
			{
				if('streamUrl' == $key)
				{
					$xmlKey = $xml->createElement('enclosure');
					$xmlKey->setAttribute('url', $value);
					
					$headers = get_headers($value, TRUE);
					$xmlKey->setAttribute('length', $headers['Content-Length']);
					$xmlKey->setAttribute('type', $headers['Content-Type']);
				}
				else
				{
					$xmlKey = $xml->createElement('id' == $key ? 'guid' : $key, $value);
				}
				
				$item->appendChild($xmlKey);
			}
			$channel->appendChild($item);
		}
		
		$rss = $xml->createElement('rss');
		$rss->setAttribute('version', '2.0');
		$rss->appendChild($channel);
		
		$xml->appendChild($rss);
		
		$res = $xml->saveXML();
		if($isReturn)
		{
			return $res;
		}
		else
		{
			exit($res);
		}
	}
}
