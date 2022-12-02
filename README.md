# mq服务

# 启动消费服务
```php

/**
 * 默认消费队列，纯rbmq处理
 */
class DefaultConsumeServer
{
    public function start()
    {

        $config = [
            'main_ip' => '10.1.160.254',
            'rbmq' => [
                'host' => '10.1.160.254',
                'port' => 5672,
                'user' => 'guest',
                'pass' => 'guest',
                'vhost' => '/',
                'debug' => true,

            ],
            'coroutine' => false,
        ];
        ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDefaultConsume(function($message){   //使用闭包 config的coroutine必须为false
            $message->ack();
            var_dump(json_decode($message->body, true));
        });
    }
}

require_once __DIR__.'/../../../vendor/autoload.php';
(new DefaultConsumeServer())->start();




/**
 * 延时队列，纯rbmq处理
 */
class DelayConsumeServer
{
    public function start()
    {

        $config = [
            'main_ip' => '10.1.160.254',
            'rbmq' => [
                'host' => '10.1.160.254',
                'port' => 5672,
                'user' => 'guest',
                'pass' => 'guest',
                'vhost' => '/',
                'debug' => true,

            ],
            'coroutine' => false,
        ];
        ConsumeServer::getInstance()->setConfig($config);
        ConsumeServer::getInstance()->startDelayConsume(function($message){   //使用闭包 config的coroutine必须为false
            $message->ack();
            var_dump($message->body);
        });
    }
}

require_once __DIR__.'/../../../vendor/autoload.php';
(new DelayConsumeServer())->start();

```


## 发送mq消息
```php


require_once __DIR__.'/../../../vendor/autoload.php';



$config = [
    'main_ip' => '10.1.160.254',
    'rbmq' => [
        'host' => '10.1.160.254',
        'port' => 5672,
        'user' => 'guest',
        'pass' => 'guest',
        'vhost' => '/',
        'debug' => true,
    ],
    'coroutine' => false,
];

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


//todo 发送协程消息
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
