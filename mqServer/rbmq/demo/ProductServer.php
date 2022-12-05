<?php

namespace mqServer\rbmq\demo;

use mqServer\rbmq\server\ProducerServer;


require_once __DIR__ . '/../../../vendor/autoload.php';
$config = require_once __DIR__ . '/config.php';

ProducerServer::getInstance()->setConfig($config);

$time = time();
//发送普通消息 第二个参数是延时，0则为普通数据
$res = ProducerServer::getInstance()->pushMessage([
    'type' => 'default_message',
    'msg' => '普通内容',
    'time_stamp' => $time,
    'time_date' => date('Y-m-d H:i:s', $time)
], 3, true);


// 使用协程发消息
//ProducerServer::getInstance()->pushMessageCoroutine([
//    'type' => 'deafult_message',
//    'msg' => '普通内容',
//    'time_stamp' => $time,
//    'time_date' => date('Y-m-d H:i:s', $time)
//]);

// 单独发udp消息
//SwooleUdpClientService::getInstance()->setConfig($config);
//$body = SwooleUdpClientService::getInstance()->buildBody('ccc', ['asdf' => date('Y-m-d H:i:s')]);
//SwooleUdpClientService::getInstance()->CoroutineSend($body);
var_dump($res ?? []);