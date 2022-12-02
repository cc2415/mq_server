<?php

namespace mqServer\Service\Dispatch;

use mqServer\rbmq\Service\BaseService;
use Swoole\Coroutine;

class TaskService extends BaseService
{

    public function task($data, $clientInfo, $server)
    {
        var_dump(static::class);
        $data = json_decode($data, true);
        echo '------------------数据----------------------------'.PHP_EOL;
        var_dump($data);
//        sleep(10);
        Coroutine::sleep(10);
        echo '完成' . PHP_EOL;
        echo '----------------------------------------------'.PHP_EOL;
    }
}