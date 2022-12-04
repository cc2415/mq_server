<?php

namespace mqServer\rbmq\demo;


use mqServer\rbmq\server\ConsumeServer;

/**
 * 默认消费队列，纯rbmq处理
 */
class DemoDefaultConsumeServer
{
    public function start()
    {
        $config = require_once __DIR__ . '/config.php';
        ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDefaultConsume(function ($message) {   //使用闭包 config的coroutine必须为false
            $message->ack();
            var_dump(json_decode($message->body, true));
        });
    }
}

require_once __DIR__ . '/../../../vendor/autoload.php';
(new DemoDefaultConsumeServer())->start();
