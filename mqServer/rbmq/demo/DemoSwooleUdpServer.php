<?php

namespace mqServer\rbmq\demo;


use mqServer\rbmq\server\ConsumeServer;
use mqServer\rbmq\server\SwooleUdpServer;

/**
 * 默认消费队列，启动swoole协程处理任务
 */
class DemoSwooleUdpServer
{
    /**
     * 启动udp服务，通过协程高效处理数据
     */
    public function startSwooleUdp()
    {
        $config = require_once __DIR__ . '/config.php';
        SwooleUdpServer::getInstance()->setConfig($config);
        SwooleUdpServer::getInstance()->start(function ($data, $clientInfo, $server) {
            var_dump(json_decode($data));
        });
    }
}

require_once __DIR__ . '/../../../vendor/autoload.php';
(new DemoSwooleUdpServer())->startSwooleUdp();
