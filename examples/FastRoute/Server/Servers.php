<?php

namespace Routes\Server;

use Routes\Config\Config;
use Routes\Config\ConfigFactory;
use FastRoute\RouteCollector;
use Swow\Http\Server\Connection;
use function FastRoute\simpleDispatcher;
use FastRoute\Dispatcher;

class Servers
{
    public function __construct()
    {

    }
    public function onRequest(Connection $connection)
    {
        $request = $connection->recvHttpRequest();
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();
        $routes = require 'examples/config/routes.php';

        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) use ($routes){
            var_dump('aaaaa');
            var_dump(gettype($routes));
            foreach ($routes as $route){
                [$httpMethod,$path,$handler] = $route;
                $routeCollector->addRoute($httpMethod,$path,$handler);
            }
        });
        $routeInfo = $dispatcher->dispatch($method,$uri);
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