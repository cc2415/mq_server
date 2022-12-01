<?php

namespace mqServer\rbmq\server;

use mqServer\rbmq\Service\BaseServer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class BaseRbmqServer extends BaseServer

{
    private $had_init_queue_list = [];//

    private $channel = null;//mq的channel

    private $connection = null;//mq的链接

    private $is_init = false;//是否初始化了


    private $delay_channel = null;//mq的channel

    private $delay_connection = null;//mq的链接

    public $dead_exchange = 'default_dead_exchange';//死信交换机

    public $dead_queue = 'default_dead_queue';//死信队列

    public $dead_route_key = 'default_dead_route_key';//死信route-key

    public $queue = 'default_queue';//普通队列

    public $defautlt_exchange = 'default_exchange';//普通交换机

    public $delay_queue = 'default_delay_queue';//延时队列

    public $delay_exchange = 'default_delay_exchange';//延时交换机

    public $delay_routing_key = 'default_delay_rout_key';//延时rout-key


    public function setDeadExchangeName($name)
    {
        $this->dead_exchange = $name;
    }

    public function getDeadExchange(): string
    {
        return $this->dead_exchange;
    }

    public function setDeadQueue($name)
    {
        $this->dead_queue = $name;
    }

    public function getDeadQueue()
    {
        return $this->dead_queue;
    }

    public function setDeadRoutKey($name)
    {
        $this->dead_route_key = $name;
    }

    public function getDeadRoutKey()
    {
        return $this->dead_route_key;
    }

    public function getDefaultQueue()
    {
        return $this->queue;
    }

    public function getDefaultExchange()
    {
        return $this->defautlt_exchange;
    }

    public function getDelayQueue()
    {
        return $this->delay_queue;
    }

    public function setDelayQueue($name)
    {
        $this->delay_queue = $name;
    }

    public function getDelayRoutKey()
    {
        return $this->delay_routing_key;
    }


    /**
     * @return AMQPStreamConnection|null
     */
    public function getDefaultConnection(): AMQPStreamConnection
    {
        if (!$this->connection) {
            $this->connection = new AMQPStreamConnection($this->getConfig()['rbmq']['host'], $this->getConfig()['rbmq']['port'], $this->getConfig()['rbmq']['user'], $this->getConfig()['rbmq']['pass'], $this->getConfig()['rbmq']['vhost'],
                false,
                'AMQPLAIN',
                null,
                'en_US',
                3.0,
                3.0,
                null,
                true,
                30
            );
        }
        return $this->connection;
    }


    /**
     * @return \PhpAmqpLib\Channel\AbstractChannel|\PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel(): \PhpAmqpLib\Channel\AbstractChannel
    {
        if (!$this->channel) {
            $this->channel = $this->getDefaultConnection()->channel();;
        }
        return $this->channel;
    }


    public function getDelayConnection()
    {
        if (isNull($this->delay_connection)) {
            $this->delay_connection = new AMQPStreamConnection($this->getConfig()['rbmq']['host'], $this->getConfig()['rbmq']['port'], $this->getConfig()['rbmq']['user'], $this->getConfig()['rbmq']['pass'], $this->getConfig()['rbmq']['vhost'],
                false,
                'AMQPLAIN',
                null,
                'en_US',
                3.0,
                3.0,
                null,
                false,
                30
            );
        }
        return $this->delay_connection;
    }

    public function getDelayChannel(): \PhpAmqpLib\Channel\AbstractChannel
    {
        if (isNull($this->channel)) {
            $this->delay_channel = $this->getDelayConnection()->channel();;
        }
        return $this->delay_channel;
    }

    /**
     * 初始化队列和交换机
     */
    public function initQueue()
    {
        if (!$this->is_init) {


            /**
             * -----------------------------初始化死信队列-----------------------------
             */
            //
            $channel = $this->getChannel();
            //死信交换机声明
            $channel->exchange_declare($this->dead_exchange, AMQPExchangeType::DIRECT, false, true, false);
            //延时交换机
            $channel->exchange_declare($this->delay_exchange, AMQPExchangeType::DIRECT);

            //往延时交换机发送消息，因为没有处理消息，然后消息超时，就会被发送到声明延时队列的时候设置好的超时后要发送的交换机上
            //所以消费那里只需要读取死信的队列就可以了
            $arguments = new AMQPTable();
//            $arguments->set('x-message-ttl', 30*1000);//针对消息延迟
            $arguments->set('x-dead-letter-exchange', $this->dead_exchange);//用来设置死信后发送的交换机
            $arguments->set('x-dead-letter-routing-key', $this->dead_route_key);//用来设置死信的routingKey
            //延时队列声明
            $channel->queue_declare($this->delay_queue, false, true, false, false, false, $arguments);
            //死信队列声明
            $channel->queue_declare($this->dead_queue, false, true, false, false, false);

            //绑定
            $channel->queue_bind($this->delay_queue, $this->delay_exchange, $this->delay_routing_key);
            $channel->queue_bind($this->dead_queue, $this->dead_exchange, $this->dead_route_key);

//            $head = array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT, 'expiration' => $expiration * 1000);

            /**
             * -----------------------------初始化普通队列-----------------------------
             */
            //
            $channel->queue_declare($this->queue, false, true, false, false);
            //普通交换机声明
            $channel->exchange_declare($this->defautlt_exchange, AMQPExchangeType::DIRECT, false, true, false);
            $channel->queue_bind($this->queue, $this->defautlt_exchange);
            $this->is_init = true;
        }
    }

    public function releaseRbmq()
    {
        $this->connection = null;
        $this->channel = null;

    }

    /**
     * 发送普通消息
     * @param $data
     */
    public function pushDefaultMessage(array $data)
    {
        $this->initQueue();
        $messageBody = json_encode($data);
        $message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $this->getChannel()->basic_publish($message, $this->defautlt_exchange);
        $this->releaseRbmq();
    }

    /**
     * 发送延时消息
     * @param $data
     * @param $expiration
     */
    public function pushDelayMessage(array $data, $expiration)
    {
        $this->initQueue();
        $messageBody = json_encode($data);
        $head = array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT, 'expiration' => $expiration * 1000);
        $message = new AMQPMessage($messageBody, $head);
        $channel = $this->getChannel();
        $channel->basic_publish($message, $this->delay_exchange, $this->delay_routing_key);
        $this->releaseRbmq();

    }
}