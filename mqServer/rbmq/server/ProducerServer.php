<?php
namespace mqServer\rbmq\server;

use mqServer\Service\rbmq\BaseRbmqService;

class ProducerServer extends BaseRbmqService
{
    /**
     * 发送普通消息
     * @param array $data 数据
     * @param int $expiration 延时 /秒
     */
    public function pushMessage($data = [],  $expiration = 0)
    {
        if ($expiration > 0) {
            $this->pushDelayMessage($data, $expiration);
        } else {
            $this->pushDefaultMessage($data);
        }
    }

    /**
     * 协程处理数据 发送消息
     * @param array $data
     * @param int $expiration 延时 /秒
     */
    public function pushMessageCoroutine($data = [],  $expiration = 0)
    {
        go(function () use ($data, $expiration) {
            if ($expiration > 0) {
                $this->pushDelayMessage($data, $expiration);
            } else {
                $this->pushDefaultMessage($data);
            }
        });
    }

    public function buildPushContent()
    {
        
    }
}


