<?php

namespace mqServer\rbmq\demo;


use src\rbmq\server\ConsumeServer;

/**
 * 默认消费队列
 */
class DefaultConsumeServer
{
    public function start()
    {
        ConsumeServer::getInstance()->startDefaultConsume();
    }
}
