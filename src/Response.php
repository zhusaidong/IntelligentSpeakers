<?php
/**
* Response
*
* @author zhusaidong [zhusaidong@gmail.com]
*/
abstract class Response
{
	public abstract function getResponse($request,$params = []);
}
