<?php
require_once __DIR__ . '/../..//vendor/autoload.php';
use Workerman\Worker;
use PHPSocketIO\SocketIO;
use Workerman\Lib\Timer;

// 创建socket.io服务端，监听2021端口
$io = new SocketIO(3120);
// 当有客户端连接时打印一行文字
$io->on('connection', function($socket)use($io){
    $socket->addedUser = false;
    echo "new connection coming\n";

//    $io->emit('msg', '这个是消息内容...');
    $socket->on('msg', function($msg)use($io){
        echo "new $msg\n";
        // 触发所有客户端定义的chat message from server事件
        $io->emit('msg', ['content'=>'你好','time'=>time()]);
    });

    $socket->on('adduser', function($username)use($socket){
        global $usernames, $numUsers;

        // we store the username in the socket session for this client
        $socket->username = $username['name'];
        // add the client's username to the global list
        $usernames[$username['name']] = $username['name'];
        ++$numUsers;
        $socket->addedUser = true;
        $socket->emit('login', array(
            'numUsers' => $numUsers,
            'username' => $socket->username,
            'time'=>time()
        ));

        $socket->join('test');
       // var_dump($socket->to('test'));

        // echo globally (all clients) that a person has connected
        $socket->broadcast->emit('userjoined', array(
            'username' => $socket->username,
            'numUsers' => $numUsers
        ));
    });
    $socket->on('matchgame', function($matchdata)use($io){
        global $matchuser;
        $matchuser[] = $matchdata['username'];
        //加入匹配后
//        Timer::add(2,function(){
//            global $matchuser;
//            $matchusercount = count($matchuser);
//            if( $matchusercount >=2 ){
//                $user1 = array_pop($matchusercount);
//            }
//        });
        $io->join('test');
        var_dump($io->to('test'));
    });
});

Worker::runAll();