<?php

namespace mqServer\rbmq\demo;



use mqServer\rbmq\server\ConsumeServer;

/**
 * 默认消费队列，纯rbmq处理
 */
class DefaultConsumeServer
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
        ConsumeServer::getInstance()->startDefaultConsume(function($message){   //使用闭包 config的coroutine必须为false
            $message->ack();
            var_dump(json_decode($message->body, true));
        });
    }
}

require_once __DIR__.'/../../../vendor/autoload.php';
(new DefaultConsumeServer())->start();
