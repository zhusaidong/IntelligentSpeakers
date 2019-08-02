<?php
/**
 * Request
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\IntelligentSpeakers\Interfaces;

interface RequestInterface
{
	/**
	 * request handle
	 *
	 * @param array $input
	 *
	 * @return mixed
	 */
	public function handle(array $input);
}
