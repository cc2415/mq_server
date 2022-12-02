<?php

namespace console;



use mqServer\rbmq\server\ConsumeServer;

/**
 * 延时队列，纯rbmq处理
 */
class DelayConsumeServer
{
    public function start()
    {

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
        ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDelayConsume(function($message){   //使用闭包 config的coroutine必须为false
            $message->ack();
            var_dump($message->body);
        });
    }
}

require_once __DIR__.'/../../../vendor/autoload.php';
(new DelayConsumeServer())->start();
