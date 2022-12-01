<?php


use mqServer\rbmq\AAA;
use mqServer\rbmq\server\ConsumeServer;

require_once __DIR__.'/vendor/autoload.php';

$config = [
    'main_ip' => '10.1.160.254',
    'rbmq' => [
        'host' => '10.1.160.254',
        'port' => 5672,
        'user' => 'guest',
        'pass' => 'guest',
        'vhost' => '/',
        'debug' => true,

    ]
];
ConsumeServer::getInstance()->setConfig($config);
ConsumeServer::getInstance()->startDefaultConsume();

AAA::call();
// BBB::call();