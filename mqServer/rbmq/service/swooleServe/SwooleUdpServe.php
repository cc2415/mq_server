<?php
//
//namespace mqServer\service\swooleServe;
//
//use http\Env\Request;
//use mqServer\rbmq\server\ConsumeServer;
//use mqServer\rbmq\Service\BaseService;
//use src\Service\rbmq\ConsumeServer;
//use src\Service\rbmq\ProducerServer;
//use Swoole\Server;
//
//require_once __DIR__ . '/../../../web/index.php';
//
//use src\Service\rbmq\BaseRbmq22;
//use Swoole\Table;
//
//class SwooleUdpServe extends BaseService
//{
//    use BaseRbmq22;
//
//    public function start()
//    {
//
//        ConsumeServer::getInstance()->startDefaultConsume();
//        $server = new Server('127.0.0.1', 9502, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
//
//        //共享内存表
//        $table = new Table(1024);
//        $table->column('consume_name', Table::TYPE_STRING, 100);
//        $table->column('state', Table::TYPE_INT, 1);
//        $table->column('num', Table::TYPE_INT, 2);
//        $table->create();
//
//        $table->set('default_consume', ['consume_name' => 'default_consume', 'state' => 1, 'num' => 1]);
//        $table->set('delay_consume', ['consume_name' => 'delay_consume', 'state' => 1, 'num' => 1]);
//
//        var_dump($table->get('delay_consume'));
//        var_dump($table->getMemorySize());
//
//
//        //监听数据接收事件
//        $server->on('Packet', function ($server, $data, $clientInfo) use ($table) {
//            $data = json_decode($data);
//            var_dump($clientInfo, $data);
//            $server->sendto($clientInfo['address'], $clientInfo['port'], "Server：{$data}");
//        });
//
//        //启动服务器
//        $server->start();
//    }
//
//}
//
////php src/Service/swooleServe/SwooleUdpServe.php
//(new SwooleUdpServe())->start();