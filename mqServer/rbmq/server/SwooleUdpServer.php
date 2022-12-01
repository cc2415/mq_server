<?php
namespace mqServer\rbmq\server;

use mqServer\Service\Dispatch\TaskService;
use Swoole\Server;
use Swoole\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Swoole udp服务
 */
class SwooleUdpServer
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

    public function start()
    {

        //共享内存表
        $table = new Table(1024);
        $table->column('consume_name', Table::TYPE_STRING, 100);
        $table->column('state', Table::TYPE_INT, 1);
        $table->column('num', Table::TYPE_INT, 2);
        $table->create();

        $table->set('default_consume', ['consume_name' => 'default_consume', 'state' => 1, 'num' => 1]);
        $table->set('delay_consume', ['consume_name' => 'delay_consume', 'state' => 1, 'num' => 1]);

        var_dump($table->get('delay_consume'));
        var_dump($table->getMemorySize());

        echo '启动' . PHP_EOL;
        $server = new Server('127.0.0.1', 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);

//监听数据接收事件
        $server->on('Packet', function ($server, $data, $clientInfo) {
            go(function () use ($server, $clientInfo, $data) {
                echo "*******接收到数据了，协程处理".PHP_EOL;
                TaskService::getInstance()->task($data, $clientInfo, $server);
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
    public static function Packet($server, $data, $clientInfo)
    {
        var_dump($clientInfo);
        $server->sendto($clientInfo['address'], $clientInfo['port'], "Server：{$data}");
        //分发任务
        go(function () use ($data) {
            TaskService::getInstance()->task($data);
        });
    }
}
//$server = new Server('127.0.0.1', 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
//$server->on('Packet', 'SwooleUdpServe::Packet');

(new SwooleUdpServer())->start();
