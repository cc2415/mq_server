<?php

namespace mqServer\rbmq\Service\swooleClient;

use mqServer\rbmq\Service\BaseService;
use Swoole\Coroutine\Client;
use function PHPUnit\Framework\throwException;
use function Swoole\Coroutine\run;

class SwooleUdpClientService extends BaseService
{

    public function buildBody($type,$data)
    {
        return ['type' => $type, 'data' => $data];
    }

    /**
     * 协程发送数据UDP
     * @param array $data
     */
    public function coroutineSend($data=[])
    {

        $config = $this->getConfig();
        go(function () use ($config, $data) {
            //协程
            $client = new Client(SWOOLE_SOCK_UDP);
            if (!$client->connect($config['swoole_udp']['host'], $config['swoole_udp']['port'], 0.5)) {
                throw new \Exception('协程upd链接失败：'.$client->errCode, 4444);
            }
            if (is_array($data)) {
                $data = json_encode($data, true);
            }
            $client->send(strval($data));
            $client->close();
        });
    }

    /**
     * 发送udp数据
     * @param array $data
     * @throws \Exception
     */
    public function send($data=[])
    {
        $config = $this->getConfig();
        $client = new \Swoole\Client(SWOOLE_SOCK_UDP);
        if (!$client->connect($config['swoole_udp']['host'], $config['swoole_udp']['port'], 5)) {
            throw new \Exception('upd链接失败：'.$client->errCode, 4444);
        }
        $client->send(json_encode($data));
        $client->close();
    }
}