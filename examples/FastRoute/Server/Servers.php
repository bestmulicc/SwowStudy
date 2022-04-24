<?php

namespace Routes\Server;

use Routes\Config\Config;
use Routes\Config\ConfigFactory;
use FastRoute\RouteCollector;
use Swow\Http\Server\Connection;
use function FastRoute\simpleDispatcher;
use FastRoute\Dispatcher;
use Routes\Router\DispatcherFactory;

class Servers
{
    /*
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var DispatcherFactory
     */
    protected $dispatcherFactory;

    public function __construct(DispatcherFactory $dispatcherFactory)
    {
        $this->dispatcherFactory = $dispatcherFactory;
        $this->dispatcher = $this->dispatcherFactory->getDispatcher('http');
    }
    public function onRequest(Connection $connection)
    {
        $request = $connection->recvHttpRequest();
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();

        $routeInfo = $this->dispatcher->dispatch($method,$uri);
        var_dump($routeInfo);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $connection->respond("NOT FOUND");
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $connection->respond("METHOD NOT FOUND");
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                [$controller, $action] = $handler;
                $instance = new $controller();
                $result = $instance->$action(...$vars);
                $connection->respond($result);
                break;
        }
    }
}

//$routes = require BASE_PATH.'/config/routes.php';
//$dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) use ($routes){
//    foreach ($routes as $route){
//        [$httpMethod,$path,$handler] = $route;
//        $routeCollector->addRoute($httpMethod,$path,$handler);
//    }
//});
//$routeInfo = $dispatcher->dispatch($method,$uri);
//switch ($routeInfo[0]) {
//    case Dispatcher::NOT_FOUND:
//        $connection->respond("NOT FOUND");
//        break;
//    case Dispatcher::METHOD_NOT_ALLOWED:
//        $connection->respond("METHOD NOT FOUND");
//        break;
//    case Dispatcher::FOUND:
//        $handler = $routeInfo[1];
//        $vars = $routeInfo[2];
//        [$controller, $action] = $handler;
//        $instance = new $controller();
//        $result = $instance->$action(...$vars);
//        $connection->respond($result);
//        break;
//}