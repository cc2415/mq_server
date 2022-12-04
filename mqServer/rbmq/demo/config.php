<?php

return [
    'main_ip' => '192.168.10.5',
    'rbmq' => [
        'host' => '192.168.10.5',
        'port' => 5672,
        'user' => 'guest',
        'pass' => 'guest',
        'vhost' => '/',
        'debug' => true,
    ],
    'coroutine' => true,    //使用swoole协程处理mq接收到的数据
    'swoole_udp' => [   //swoole upd的配置
        'port' => 9502,
        'host' => '127.0.0.1'
    ]
];