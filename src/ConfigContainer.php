<?php

namespace Imanee;

abstract class ConfigContainer
{
    protected $config;

    /**
     * @param array $defaults
     * @param array $values
     */
    public function __construct(array $defaults = [], array $values = [])
    {
        $this->config = array_merge($defaults, $values);
    }

    /**
     * @param $param
     *
     * @return string|null
     */
    public function __get($param)
    {
        return $this->get($param);
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
     * @param string      $param
     * @param string|null $default
     *
     * @return string|null
     */
    public function get($param, $default = null)
    {
        return array_key_exists($param, $this->config) ? $this->config[$param] : $default;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return ConfigContainer $this
     */
    public function set($name, $value)
    {
        $this->config[$name] = $value;

        return $this;
    }
}
