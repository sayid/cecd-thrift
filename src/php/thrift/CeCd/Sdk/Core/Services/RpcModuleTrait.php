<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/30
 * Time: 15:21
 */

namespace Thrift\CeCd\Sdk\Core\Services;

trait RpcModuleTrait
{
    private $host;

    private $port;

    public function __construct()
    {
    
    }

    /**
     * 魔术方法自动调用rpc类
     * @param $class
     * @return Rpc
     */
    public function __get($class)
    {
        $packge = substr(get_called_class(), 0, strrpos(get_called_class(), "\\"));
        if (substr($class, strlen($class) - 3) == 'Lib') {
            $class_load = $packge . "\Libraries\\".$class;
        } elseif (substr($class, strlen($class) - 5) == 'Model') {
            $class_load = $packge . "\Models\\".$class;
        }
        return new Rpc($class_load, $this);;
    }

    public function  getRpc($classname) : RpcInterface
    {
        $proxy = new Rpc($classname, $this);
        return $proxy;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        if (!empty($this->host)) {
            return $this->host;
        } else {
            $host = GEnv($this->config_key.".host");
            return $host;
        }

    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        if (!empty($this->port)) {
            return $this->port;
        } else {
            $host = GEnv($this->config_key.".port");
            return $host;
        }
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }
}