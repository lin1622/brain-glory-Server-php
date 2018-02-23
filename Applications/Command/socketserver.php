<?php
require_once __DIR__ . '/../..//vendor/autoload.php';
use Workerman\Worker;
use PHPSocketIO\SocketIO;
use Workerman\Lib\Timer;

// 创建socket.io服务端，监听2021端口
$io = new SocketIO(3120);
// 当有客户端连接时打印一行文字
$io->on('connection', function($socket)use($socket,$io){

    $socket->addedUser = false;
    echo "new connection coming\n";
    var_dump($io->nsps);
    //接受消息
    $socket->on('msg', function($msg)use($socket){
        echo "new $msg\n";
        // 触发所有客户端定义的chat message from server事件
        $socket->emit('msg', ['content'=>'你好','time'=>time()]);
    });

    //用户登录
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

    //匹配对手
    $socket->on('matchgame', function($matchdata)use($socket){
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
        $socket->join('test');
    });


    // when the user disconnects.. perform this
    $socket->on('disconnect', function () use($socket) {
        global $usernames, $numUsers;
        // remove the username from global usernames list
        if($socket->addedUser) {
            unset($usernames[$socket->username]);
            --$numUsers;
            // echo globally that this client has left
            $socket->broadcast->emit('user left', array(
                'username' => $socket->username,
                'numUsers' => $numUsers
            ));
        }
    });

});

Worker::runAll();