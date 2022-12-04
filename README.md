# mq服务
**composer require cheng_util/mqserver**
## 配置
```php
$config = [
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
```
## 启动消费服务
```php
/**
 * 默认消费队列，纯rbmq处理
 */
 
require_once __DIR__.'/../../../vendor/autoload.php';
ConsumeServer::getInstance()->setConfig($config);
ConsumeServer::getInstance()->startDefaultConsume(function($message){   //使用闭包 config的coroutine必须为false
            $message->ack();
            var_dump(json_decode($message->body, true));
        });
        

/**
 * 延时队列，纯rbmq处理
 */
 
require_once __DIR__.'/../../../vendor/autoload.php';
ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDelayConsume(function($message){   //使用闭包 config的coroutine必须为false
            $message->ack();
            var_dump($message->body);
        });
        

```


## 发送mq消息
```php

require_once __DIR__.'/../../../vendor/autoload.php';
ProducerServer::getInstance()->setConfig($config);

$time = time();
//发送普通消息 第二个参数是延时，0则为普通数据
$res = ProducerServer::getInstance()->pushMessage([
    'type' => 'default_message',
    'msg' => '普通内容',
    'time_stamp' => $time,
    'time_date' => date('Y-m-d H:i:s', $time)
], 3, true);

var_dump($res);


//协程发消息
ProducerServer::getInstance()->pushMessageCoroutine([
                'type' => 'deafult_message',
                'msg' => '普通内容',
                'time_stamp' => $time,
                'time_date' => date('Y-m-d H:i:s', $time)
            ]);
```

## 单独发送udp消息，不经过mq
```php
// 单独发udp消息
SwooleUdpClientService::getInstance()->setConfig($config);
$body = SwooleUdpClientService::getInstance()->buildBody('ccc', ['asdf' => date('Y-m-d H:i:s')]);
SwooleUdpClientService::getInstance()->CoroutineSend($body);
```
### demo说明：
1、普通消费

    DemoDefaultConsumeServer.php 启动普通消费队列

    DemoDelayConsumeServer 启动延时队列
2、swoole的协程消费

    先启动对应的mq服务，普通或者延时，然后启动DemoSwooleUdpServer.php 这个udp协程处理

