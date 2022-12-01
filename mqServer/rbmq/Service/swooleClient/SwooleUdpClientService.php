<?php

namespace mqServer\Service\swooleClient;

use mqServer\Service\BaseService;
use Swoole\Coroutine\Client;
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

        go(function () use ($data) {
            //协程
            echo '协程发送' . PHP_EOL;
            $client = new Client(SWOOLE_SOCK_UDP);
            $ip = MAC_IP;
            $ip = '127.0.0.1';
            if (!$client->connect($ip, 9502, 0.5)) {
                echo "connect failed. Error: {$client->errCode}\n";
            }
            if (is_array($data)) {
                $data = json_encode($data, true);
            }
            $client->send(strval($data));
            $client->close();
        });
    }

    public function send($data=[])
    {
        $client = new \Swoole\Client(SWOOLE_SOCK_UDP);
        $ip = MAC_IP;
        $ip = '127.0.0.1';
        if (!$client->connect($ip, 9502, 5)) {
            exit("connect failed. Error: {$client->errCode}\n");
        }
        echo '普通发送' . PHP_EOL;
        if ($client->isConnected()){
            echo '链接成功' . PHP_EOL;
        }
        $client->send(json_encode($data));
        $client->close();
    }
}