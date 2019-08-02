<?php
/**
 * Subscription Feed class
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace zhusaidong\IntelligentSpeakers\Xiaoai;

class Feed
{
	/**
	 * @var array $feed
	 */
	private $feed = [];
	
	/**
	 * Feed constructor.
	 *
	 * @param array $item
	 */
	public function __construct(array $item = [])
	{
		isset($item['pubDate']) and $item['pubDate'] = $this->pubDateToISO($item['pubDate']);
		$this->feed = $item;
	}
	
	/**
	 * pubDate 格式要求ISO 8601格式
	 *
	 * @param $pubDate
	 *
	 * @return false|string
	 */
	private function pubDateToISO($pubDate)
	{
		return date('c', $pubDate);
	}
	
	/**
	 * set
	 *
	 * @param $key
	 * @param $value
	 */
	public function __set($key, $value)
	{
		$key === 'pubDate' and $value = $this->pubDateToISO($value);
		$this->feed[$key] = $value;
	}
	
	/**
	 * get
	 *
	 * @param $key
	 *
	 * @return mixed|null
	 */
	public function __get($key)
	{
		return $this->get($key);
	}
	
	/**
	 * get
	 *
	 * @param string $attr
	 * @param null   $default
	 *
	 * @return mixed|null
	 */
	public function get(string $attr, $default = NULL)
	{
		return $this->feed[$attr] ?? $default;
	}
}
