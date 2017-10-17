<?php 

/**
* 
*/
class AdminLogin extends Admin
{
	function run($connection, $data, $worker) 
	{
		if (md5($data['pwd']) != $this->pwd) {
			throw new Exception("pwd fail");
		}

		$worker->amdinConnections[$data['admin_id']] = $connection;
		print_r(count($worker->amdinConnections));
	}
}