<?php

use Workerman\Worker;
use Workerman\Lib\Timer;
use PHPSocketIO\SocketIO;
use Symfony\Component\Yaml\Yaml;

include __DIR__ . '/../vendor/autoload.php';

$uidConnectionMap = array();

$last_online_count = 0;
$last_online_page_count = 0;

$socketPort = 3120;
$socketPushPort = 3121;

$parameters = Yaml::parseFile(__DIR__ . '/../app/config/parameters.yml');
$parameters = $parameters['parameters'];

if (!empty($parameters['socket_local_cert'])) {
    $context = array(
        'ssl' => [
            'local_cert' => $parameters['socket_local_cert'],
            'local_pk' => $parameters['socket_local_pk'],
            'verify_peer' => false
        ]
    );

} else {
    $context = [];
}
if (preg_match('/:(\d+)\/?$/', $parameters['socket_host'], $mat)) {
    $socketPort = $mat[1];
}
if (preg_match('/:(\d+)\/?$/', $parameters['socket_push_host'], $mat)) {
    $socketPushPort = $mat[1];
}

/**
 * @return \Predis\Client
 */
function getRedis()
{
    global $parameters;

    /** @var \Predis\Client $redis */
    static $redis = null;

    if (empty($redis) || $redis->isConnected() == false) {
        $redis = new \Predis\Client($parameters['redis_dsn']);
    }

    return $redis;
}

$sender_io = new SocketIO($socketPort, $context);

$sender_io->on('connection', function (\PHPSocketIO\Socket $socket) {
    $socket->on('login', function ($data) use ($socket) {
        global $uidConnectionMap, $last_online_count, $last_online_page_count;

        $user = $data['username']; $token = $data['token'];

        if ($token) {
            $redisToken = getRedis()->get('socket:'. $user);
            if (empty($redisToken) || $redisToken != $token) {
                return ;
            }
        }

        if (isset($socket->uid)) {
            return;
        }

        if (!isset($uidConnectionMap[$user])) {
            $uidConnectionMap[$user] = 0;
        }

        ++$uidConnectionMap[$user];
        $socket->join($user);
        $socket->uid = $user;

        $data = [
            'onlineCount' => $last_online_count,
            'pageCount' => $last_online_page_count
        ];

        $socket->emit('update_online_count', json_encode($data));
    });

    $socket->on('disconnect', function () use ($socket) {
        if (!isset($socket->uid)) {
            return;
        }
        global $uidConnectionMap;

        if (--$uidConnectionMap[$socket->uid] <= 0) {
            unset($uidConnectionMap[$socket->uid]);
        }
    });
});

$sender_io->on('workerStart', function () {
    global $socketPushPort;
    $inner_http_worker = new Worker('http://0.0.0.0:' . $socketPushPort);
    $inner_http_worker->onMessage = function (Workerman\Connection\ConnectionInterface $http_connection) {
        global $uidConnectionMap;
        $_POST = $_POST ? $_POST : $_GET;
        // 推送数据的url格式 type=publish&to=uid&content=xxxx
        switch (@$_POST['type']) {
            case 'publish':
                global $sender_io;
                $to = @$_POST['to'];

                if ($to) {
                    $sender_io->to($to)->emit($_POST['event'], $_POST['data']);
                } else {
                    $sender_io->emit($_POST['event'], @$_POST['data']);
                }

                if ($to && !isset($uidConnectionMap[$to])) {
                    return $http_connection->send('offline');
                } else {
                    return $http_connection->send('ok');
                }
        }
        return $http_connection->send('fail');
    };

    $inner_http_worker->listen();

    Timer::add(1, function () {
        global $uidConnectionMap, $last_online_count, $last_online_page_count, $sender_io;

        $online_count_now = count($uidConnectionMap);
        $online_page_count_now = array_sum($uidConnectionMap);

        if ($last_online_count != $online_count_now
            || $last_online_page_count != $online_page_count_now
        ) {
            $data = [
                'onlineCount' => $online_count_now,
                'pageCount' => $online_page_count_now
            ];

            $sender_io->emit('update_online_count', json_encode($data));

            $last_online_count = $online_count_now;
            $last_online_page_count = $online_page_count_now;
        }
    });
});

if (!defined('GLOBAL_START')) {
    Worker::runAll();
}
