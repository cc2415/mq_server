<?php

return [
    'main_ip' => '10.1.160.254',
    'rbmq' => [
        'host' => '10.1.160.254',
        'port' => 5672,
        'user' => 'guest',
        'pass' => 'guest',
        'vhost' => '/',
        'debug' => true,
    ],
    'coroutine' => false,    //使用swoole协程处理mq接收到的数据
    'swoole_udp' => [   //swoole upd的配置
        'port' => 9502,
        'host' => '127.0.0.1'
    ]
];