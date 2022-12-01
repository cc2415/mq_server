<?php

namespace mqServer\rbmq\demo;



use mqServer\rbmq\server\ConsumeServer;

/**
 * 默认消费队列
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

            ]
        ];
        ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDefaultConsume();
    }
}

(new DefaultConsumeServer())->start();
