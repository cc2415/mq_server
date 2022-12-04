<?php

namespace mqServer\rbmq\server;

use mqServer\rbmq\Service\BaseServer;
use mqServer\Service\Dispatch\TaskService;
use Swoole\Server;
use Swoole\Table;
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
//                TaskService::getInstance()->task($data, $clientInfo, $server);
                $deal($data, $clientInfo, $server);
                $server->sendto($clientInfo['address'], $clientInfo['port'], "Server：{$data}");
            });
        });
//启动服务器
        $server->start();
    }

    /**
     * 监听数据接收事件
     * @param $server
     * @param $data
     * @param $clientInfo
     */
//    public static function Packet($server, $data, $clientInfo)
//    {
//        var_dump($clientInfo);
//        $server->sendto($clientInfo['address'], $clientInfo['port'], "Server：{$data}");
//        //分发任务
//        go(function () use ($data) {
//            TaskService::getInstance()->task($data);
//        });
//    }
}
