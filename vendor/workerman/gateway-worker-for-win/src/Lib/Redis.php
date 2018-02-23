<?php
/**
 * Created by PhpStorm.
 * User: DDX
 * Date: 2018/2/23
 * Time: 15:34
 */
namespace GatewayWorker\Lib;

use Config\Redis as RedisConfig;
use Exception;

/**
 * 数据库类
 */
class Redis
{
    /**
     * 实例数组
     *
     * @var array
     */
    protected static $instance = array();

    /**
     * 获取实例
     *
     * @param string $config_name
     * @return DbConnection
     * @throws Exception
     */
    public static function instance($config_name)
    {
//        if (!isset(RedisConfig::$$config_name)) {
//            echo "\\RedisConfig\\RedisConfig::$config_name not set\n";
//            throw new Exception("\\RedisConfig\\Redis::$config_name not set\n");
//        }

        if (empty(self::$instance[$config_name])) {

            echo $config_name;
            $config                       = RedisConfig::redis();
            print_r($config);
//            $redis  = new \Redis();
//            $redis -> connect($config['host'], $config['port']);
//            $redis -> auth('');
//            $redis -> select(0);
//            self::$instance[$config_name] = $redis;
        }
//        echo 'redisconnect';
//        return self::$instance[$config_name];
    }

    /**
     * 关闭数据库实例
     *
     * @param string $config_name
     */
    public static function close($config_name)
    {
        if (isset(self::$instance[$config_name])) {
            self::$instance[$config_name]->closeConnection();
            self::$instance[$config_name] = null;
        }
    }

    /**
     * 关闭所有数据库实例
     */
    public static function closeAll()
    {
        foreach (self::$instance as $connection) {
            $connection->closeConnection();
        }
        self::$instance = array();
    }
}
