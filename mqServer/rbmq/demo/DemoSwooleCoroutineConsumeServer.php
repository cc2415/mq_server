<?php

namespace mqServer\rbmq\demo;


use mqServer\rbmq\server\ConsumeServer;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * 默认消费队列，swoole协程处理任务
 */
class DemoSwooleCoroutineConsumeServer
{
    /**
     * 启动mq，mq接收数据通过upd发送给udp服务
     */
    public function startMq()
    {
        $config = require_once __DIR__ . '/config.php';
        ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDefaultConsume(null);
    }
}

(new DemoSwooleCoroutineConsumeServer())->startMq();
