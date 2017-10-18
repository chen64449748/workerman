<?php 

/**
* 
*/
class OrderAdminSend extends Admin
{
	function run($connection, $data, $worker)
	{
		if (md5($data['pwd']) != $this->pwd) {
			throw new Exception("pwd fail");
		}

		if (!$worker->amdinConnections) {throw new Exception("no admin");}

		$return_data = array('order_id'=> $data['order_id'], 'message'=> '您有新的订单，请注意查收');

		foreach ($worker->amdinConnections as $value) {
			$value->send(json_encode(array('action'=> 'OrderAdminSend', 'data'=> $return_data)));
		}

	}
}