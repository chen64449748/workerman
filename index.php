<?php 

use Workerman\Worker;

require_once 'Autoloader.php';

$worker = new Worker('websocket://0.0.0.0:8282');
$worker->uidConnections = array();
$worker->amdinConnections = array();

function sendMessageByUid($uid, $message)
{
    global $worker;
    if(isset($worker->uidConnections[$uid]))
    {
        $connection = $worker->uidConnections[$uid];
        $connection->send($message);
        return true;
    }
    return false;
}

$worker->onWorkerStart = function ($worker) {
	// 开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符
    $inner_text_worker = new Worker('Text://0.0.0.0:5678');
    $inner_text_worker->onMessage = function($connection, $buffer)
    {
        global $worker;
        // $data数组格式，里面有uid，表示向那个uid的页面推送数据
        try {
            $events = new Envents();
            $data = json_decode($buffer, true);
            $obj = $events->getInstance($data['action']);
            $obj->run($connection, $data, $worker);
        } catch (Exception $e) {
            echo $e->getMessage().PHP_EOL;          
        }
    };
    $inner_text_worker->listen();
};


$worker->onMessage = function ($connection, $buffer) use ($worker){
    try {
        $events = new Envents();
        $data = json_decode($buffer, true);
        $obj = $events->getInstance($data['action']);
        $obj->run($connection, $data, $worker);
    } catch (Exception $e) {
        echo $e->getMessage().PHP_EOL;          
    }
};

// 1分钟检查一次
$worker->onError = function ($connection, $code, $message) 
{
    echo $message;
};

$worker->onClose = function($connection) use ($worker)
{
    $admin_id = array_search($connection, $worker->amdinConnections);
    if ($admin_id) {
        unset($worker->amdinConnections[$admin_id]);
        echo 'unset'.$admin_id.PHP_EOL;
    }
};

Worker::$daemonize = true;
Worker::$stdoutFile = '/tmp/workmanstdout.log';
Worker::$logFile = '/tmp/workerman.log';
Worker::runAll();