<?php
/**
 * Auth: 翟帅干
 * Email: zhaishuaigan@qq.com
 * Date: 2019/7/29
 * Time: 11:45
 */

require_once __DIR__ . '/libs/WorkerMan/Autoloader.php';
//require_once './libs/FileMonitor/start.php';
require_once __DIR__ . '/src/Server.php';

// 心跳间隔55秒
define('HEARTBEAT_TIME', 55);

$server = new Server();
$server->run();
