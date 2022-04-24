<?php

namespace Routes\Router;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class DispatcherFactory
{
    /**
     * @var string[]
     */
    protected $routesFiles = [BASE_PATH.'/config/routes.php'];

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var Dispatcher[]
     */
    protected $dispatchers = [];

    public function __construct()
    {
        $this->initConfigRoute();
    }
    public function getDispatcher(string $serverName):Dispatcher
    {
        if (! isset($this->dispatchers[$serverName])){
            $this->dispatchers[$serverName] = simpleDispatcher(function (RouteCollector $routeCollector){
                foreach ($this->routes as $route){
                    [$httpMethod , $path, $handler] = $route;
                    $routeCollector->addRoute($httpMethod , $path, $handler);
                }
            });
        }
        return $this->dispatchers[$serverName];
    }
    private function initConfigRoute()
    {
        foreach ($this->routesFiles as $file){

            if (file_exists($file)){
                $routes = require $file;
                $this->routes = array_merge_recursive($this->routes,$routes);
            }
        }
    }
}