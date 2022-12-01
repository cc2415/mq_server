<?php

namespace console;

use src\Service\rbmq\ConsumeServer;


/**
 * 延时队列
 */
class DelayConsumeServer
{
    public function start()
    {
        ConsumeServer::getInstance()->startDelayConsume();
    }
}
(new DelayConsumeServer())->start();
