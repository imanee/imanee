<?php

namespace Imanee;

abstract class ConfigContainer
{
    protected $config;

    public function __construct(array $defaults = [], array $values = [])
    {
        $this->config = array_merge($defaults, $values);
    }

    public function __get($param)
    {
        return isset($this->config[$param]) ? $this->config[$param] : null;
    }

    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

    public function get($param, $default = null)
    {
        return isset($this->config[$param]) ? $this->config[$param] : $default;
    }

    public function set($name, $value)
    {
        $this->config[$name] = $value;

        return $this;
    }
}
