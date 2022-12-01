<?php

namespace console;



use mqServer\rbmq\server\ConsumeServer;

/**
 * 延时队列
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

            ]
        ];
        ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDelayConsume();
    }
}
(new DelayConsumeServer())->start();
