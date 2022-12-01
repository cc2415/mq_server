<?php

namespace mqServer\rbmq\Service;


abstract class BaseService
{
    protected static $instance = [];

    protected static $config = [];
    public static function getInstance()
    {
       $class_name =  get_called_class();
        if (!isset(self::$instance[$class_name]) || $class_name instanceof self) {
            self::$instance[$class_name] = new static;
        }
        return self::$instance[$class_name];
    }

    public function  getConfig()
    {
        return self::$config;
    }

    public function setConfig($config)
    {
        self::$config = $config;
    }

}