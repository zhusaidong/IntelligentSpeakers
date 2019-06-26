<?php
/**
 * Response
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace IntelligentSpeakers;

abstract class Response
{
	/**
	 * get response
	 *
	 * @param Request $request
	 * @param array   $params
	 *
	 * @return array
	 */
	public abstract function getResponse(Request $request, array $params = []) : array;
}
