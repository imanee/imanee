<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erika
 * Date: 6/18/13
 * Time: 10:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Imanee;


class ConfigContainer {

    protected $config;

    public function __construct(array $values = [], array $defaults = [])
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