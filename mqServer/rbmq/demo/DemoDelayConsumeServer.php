<?php

namespace console;


use mqServer\rbmq\server\ConsumeServer;

/**
 * 延时队列，纯rbmq处理
 */
class DemoDelayConsumeServer
{
    public function start()
    {
        $config = require_once __DIR__ . '/config.php';
        ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDelayConsume(function ($message) {   //使用闭包 config的coroutine必须为false
            $message->ack();
            var_dump($message->body);
        });
    }
}

require_once __DIR__ . '/../../../vendor/autoload.php';
(new DemoDelayConsumeServer())->start();
