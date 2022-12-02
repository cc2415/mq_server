<?php

namespace mqServer\rbmq\demo;



use mqServer\rbmq\server\ProducerServer;


require_once __DIR__.'/../../../vendor/autoload.php';



$config = [
    'main_ip' => '10.1.160.254',
    'rbmq' => [
        'host' => '10.1.160.254',
        'port' => 5672,
        'user' => 'guest',
        'pass' => 'guest',
        'vhost' => '/',
        'debug' => true,
    ],
    'coroutine' => false,
];

ProducerServer::getInstance()->setConfig($config);

$time = time();
//发送普通消息 第二个参数是延时，0则为普通数据
$res = ProducerServer::getInstance()->pushMessage([
    'type' => 'default_message',
    'msg' => '普通内容',
    'time_stamp' => $time,
    'time_date' => date('Y-m-d H:i:s', $time)
], 3, true);

var_dump($res);

// 发送协程消息
//ProducerServer::getInstance()->pushMessageCoroutine([
//    'type' => 'deafult_message',
//    'msg' => '普通内容',
//    'time_stamp' => $time,
//    'time_date' => date('Y-m-d H:i:s', $time)
//]);
