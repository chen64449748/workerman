<?php 

// 建立socket连接到内部推送端口
$client = stream_socket_client('tcp://127.0.0.1:5678', $errno, $errmsg, 0.8);
// 推送的数据，包含uid字段，表示是给这个uid推送
$data = array('pwd'=>'admin', 'action'=> 'OrderAdminSend', 'order_id'=> 1);
// 发送数据，注意5678端口是Text协议的端口，Text协议需要在数据末尾加上换行符
stream_socket_sendto($client, json_encode($data)."\n");