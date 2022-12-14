<?php

namespace mqServer\rbmq\server;

use Swoole\Server;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Swoole udp服务
 */
class SwooleUdpServer extends BaseServer
{
    protected static $defaultName = 'app:swoole-upd-serve';

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
        // ... put here the code to create the user
    }

    public function start($deal)
    {
        $config = $this->getConfig();
        echo '启动UDP服务' . PHP_EOL;
        $server = new Server($config['swoole_udp']['host'], $config['swoole_udp']['port'], SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

        //监听数据接收事件
        $server->on('Packet', function ($server, $data, $clientInfo) use ($deal) {
            go(function () use ($deal, $server, $clientInfo, $data) {
                $deal($data, $clientInfo, $server);
                $server->sendto($clientInfo['address'], $clientInfo['port'], "Server：{$data}");
            });
        });
        //启动服务器
        $server->start();
    }
}
