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

		if (!isset($data['admin_id'])) {
			throw new Exception("登录缺少 admin_id");
		}

		$worker->amdinConnections[$data['admin_id']] = $connection;
		echo 'now connections number:'.count($worker->amdinConnections).PHP_EOL;
	}
}