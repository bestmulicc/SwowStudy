<?php
//开启严格类型检测。默认为弱类型
declare(strict_types=1);
//修改php.ini文件中的值，将内存修改为1G，程序运行结束后回归默认
ini_set('memory_limit', '1G');
! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
//加载配置文件
require __DIR__ . '/../autoload.php';

use Routes\Server\Servers;
use Swow\Coroutine;
use Swow\CoroutineException;
use Swow\Http\Server as HttpServer;
use Swow\Socket;
use Swow\SocketException;
use Routes\Router\DispatcherFactory;

$host = getenv('SERVER_HOST') ?: '127.0.0.1';
$port = (int) (getenv('SERVER_PORT') ?: 9764);
$backlog = (int) (getenv('SERVER_BACKLOG') ?: Socket::DEFAULT_BACKLOG);

$server = new HttpServer();
$server->bind($host,$port)->listen($backlog);
while (true){
    try {
        $connection = $server->acceptConnection();
        $httpServer = new Servers(new DispatcherFactory());
        Coroutine::run(static function () use ($connection,$httpServer): void {
            try {
                while (true){
                    $httpServer->onRequest($connection);
                }
            } catch (Exception $exception) {
                echo "单次请求";
            } finally {
                $connection->close();
            }
        });
    } catch (SocketException|CoroutineException $exception) {
        echo "第一层错误";
        if (in_array($exception->getCode(), [Errno::EMFILE, Errno::ENFILE, Errno::ENOMEM], true)) {
            sleep(1);
        } else {
            break;
        }
    }
}