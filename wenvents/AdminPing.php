<?php 

class AdminPing extends Admin
{
	function run($connection, $data, $worker)
	{
		if (md5($data['pwd']) != $this->pwd) {
			throw new Exception("pwd fail");
		}

		if (!$worker->amdinConnections) {return;}
			
		$return_data = array('action'=> 'AdminPing', 'message'=> '');

		if (isset($worker->amdinConnections[$data['admin_id']])) {
			$return_data['message'] = 'pong';
			$return_data = json_encode($return_data);
			$worker->amdinConnections[$data['admin_id']]->send($return_data);	
		} else {
			$return_data['message'] = 'fail';
			$return_data = json_encode($return_data);
			$connection->send($return_data);
		}
		
	}
}