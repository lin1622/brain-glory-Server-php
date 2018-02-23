<?php
/**
 * Created by PhpStorm.
 * User: DDX
 * Date: 2018/2/23
 * Time: 15:51
 */

namespace Config;


class Redis
{
    public static $config = [];
    public static function redis()
    {
        return  [
                'host' => '127.0.0.1',
                'port' => '6379',
                'options' => [
                    'parameters' => [
//                    'password' => '',
                        'database' => 0,
                    ],
                ]
        ];
    }
}