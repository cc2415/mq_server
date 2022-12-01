<?php
namespace mqServer\rbmq\server;

use mqServer\Service\swooleClient\SwooleUdpClientService;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumeServer extends BaseRbmqServer
{

    private $consumerTag = 'consumer';

    /**
     * 默认处理数据
     * @param \PhpAmqpLib\Message\AMQPMessage $message
     */
    function defaultProcessMessage($message)
    {
        $message->ack();
        SwooleUdpClientService::getInstance()->coroutineSend($message->body);
        // Send a message with the string "quit" to cancel the consumer.
        if ($message->body === 'quit') {
            $message->getChannel()->basic_cancel($message->getConsumerTag());
        }
    }

    /**
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @param \PhpAmqpLib\Connection\AbstractConnection $connection
     */
    function shutDown($channel, $connection)
    {
        $channel->close();
        $connection->close();
    }

    function DelayProcessMessage($message)
    {
        $message->ack();
        SwooleUdpClientService::getInstance()->coroutineSend($message->body);
//        echo "\n--------\n";
//        echo $message->body;
//        echo "\n--------\n";
//        $data = json_decode($message->body, true);
//        if (json_last_error()) {
//            echo "\n----------------\n";
//            echo "\n--Json解析错误---\n";
//            echo $message->body;
//            echo "\n----------------\n";
//        }
        // Send a message with the string "quit" to cancel the consumer.
        if ($message->body === 'quit') {
            $message->getChannel()->basic_cancel($message->getConsumerTag());
        }
    }

    /**
     * 启动普通队列
     */
    public function startDefaultConsume()
    {

        $connection = $this->getDefaultConnection();
        $channel = $this->getChannel();
        $queue = $this->getDefaultQueue();
        $exchange = $this->getDefaultExchange();
        $this->initQueue();
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queue, $this->consumerTag, false, false, false, false, function (AMQPMessage $message) {
            $this->defaultProcessMessage($message);
        });

        register_shutdown_function(function () use ($connection, $channel) {
            $this->shutDown($channel, $connection);
        }, $channel, $connection);

// Loop as long as the channel has callbacks registered
        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    /**
     * 启动延时队列
     */
    public function startDelayConsume()
    {

//死信处理
        $exchange = $this->getDeadExchange();
        $queue = $this->getDeadQueue();
        $consumerTag = 'consumer';
        $dead_route_key = $this->getDeadRoutKey();

        $connection = $this->getDelayConnection();
        $this->initQueue();

        $channel = $connection->channel();
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($queue, $consumerTag, false, false, false, false, function (\PhpAmqpLib\Message\AMQPMessage $message) {
            $this->DelayProcessMessage($message);
        });


        register_shutdown_function(function () use ($connection, $channel) {
            $this->shutDown($channel, $connection);
        }, $channel, $connection);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}


