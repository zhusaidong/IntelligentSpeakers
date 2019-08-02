<?php
/**
 * Response
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\IntelligentSpeakers\Interfaces;

interface ResponseInterface
{
	/**
	 * get response
	 *
	 * @param RequestInterface $request
	 * @param array            $params
	 *
	 * @return array
	 */
	public function getResponse(RequestInterface $request, array $params = []) : array;
}
