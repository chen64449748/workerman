<?php 

/**
* 
*/
class Envents
{
	function getInstance ($event)
	{
		$reflection = new ReflectionClass($event);
		$obj = $reflection->newInstance();
		if (!$obj) {
			throw new Exception("no class");
		}

		return $obj;
	}
}