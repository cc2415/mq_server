# mq服务

# 启动消费服务
```php

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

```


## 发送mq消息
```php
//发送普通消息 第二个参数是延时数据
ProducerServer::getInstance()->pushMessage([
                'type' => 'default_message',
                'msg' => '普通内容',
                'time_stamp' => $time,
                'time_date' => date('Y-m-d H:i:s', $time)
                ], 10);

// 发送协程消息
ProducerServer::getInstance()->pushMessageCoroutine([
                'type' => 'deafult_message',
                'msg' => '普通内容',
                'time_stamp' => $time,
                'time_date' => date('Y-m-d H:i:s', $time)
            ]);
```

## 发送udp消息
```php
 $body = SwooleUdpClientService::getInstance()->buildBody('ccc', ['asdf' => date('Y-m-d H:i:s')]);
 SwooleUdpClientService::getInstance()->CoroutineSend($body);
```
