<?php

namespace Routes\Config;

use Routes\Contract\ConfigInterface;

class Config implements ConfigInterface
{
    /**
     * @var array
     */
    private $configs = [];

    /**
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    public function get(string $key, $default = null)
    {
        return $this->configs[$key] ?? $default;
    }

    public function has(string $key)
    {
        return isset($this->configs[$key]);
    }

    public function set(string $key, $value)
    {
        $this->configs[$key] = $value;
    }
}