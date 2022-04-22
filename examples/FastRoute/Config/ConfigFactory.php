<?php

namespace Routes\Config;

class ConfigFactory
{
    public function __invoke()
    {
        $configs = $this->readConfig('examples/config/routes.php');
        return new Config($configs);
    }

    private function readConfig(string $string)
    {
        $config = require $string;
        if (! is_array($config)){
            return [];
        }
        return $config;
    }
}