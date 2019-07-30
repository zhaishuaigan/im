<?php
/**
 * Auth: 翟帅干
 * Email: zhaishuaigan@qq.com
 * Date: 2019/7/29
 * Time: 17:18
 */

use Workerman\Lib\Timer;
use Workerman\Worker;
use \Workerman\WebServer;

class Server
{
    public $webSocket = null;
    public $webServer = null;

    public function run()
    {
        $this->initWebServer();
        $this->initWebSocket();
        Worker::runAll();
    }

    public function initWebServer()
    {
        $this->webServer = new WebServer('http://0.0.0.0:8080');
        $this->webServer->addRoot('*', __DIR__ . '/pages/');
    }

    public function initWebSocket()
    {
        $ws            = new Worker("websocket://0.0.0.0:8081");
        $ws->onMessage = function ($connection, $data) {
            $now = time();
            // 最后接收数据时间
            $connection->lastMessageTime = $now;
            // 向客户端发送hello $data
            $connection->send($data);
        };
        // 进程启动后设置一个每秒运行一次的定时器
        $ws->onWorkerStart = function ($ws) {
            Timer::add(1, function () use ($ws) {
                $now = time();
                foreach ($ws->connections as $connection) {
                    // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                    if (empty($connection->lastMessageTime)) {
                        $connection->lastMessageTime = $now;
                        continue;
                    }
                    // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                    if ($now - $connection->lastMessageTime > HEARTBEAT_TIME) {
                        $connection->close();
                    }
                }
            });
        };
        $this->webSocket   = $ws;
    }

}