<?php
/**
 * Request
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace IntelligentSpeakers;

abstract class Request
{
	/**
	 * request handle
	 *
	 * @param array $input
	 *
	 * @return mixed
	 */
	public abstract function handle(array $input);
}
