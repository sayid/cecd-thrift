<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/30
 * Time: 15:21
 */

namespace Thrift\CeCd\Sdk\Core\Services;

use Thrift\CeCd\Sdk\Core\Client\Rpc;

trait RpcModuleTrait
{
    private $host;

    private $port;

    private $actionType;

    public function __construct()
    {
    
    }

    /**
     * 检查服务器，返回ok标示正常
     * @return mixed|string
     * @throws \Thrift\Exception\TException
     */
    public function ping()
    {
        $rpc = new Rpc("ping", $this);
        return $rpc->callRpc("ping", "ping", []);
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

    /**
     * 设置同步/异步请求 默认同步
     * @param int $actionType
     * @return $this
     */
    public function setMode(int $actionType = 0) : RpcModuleIf
    {
        $this->actionType = $actionType;
        return $this;
    }

    public function getActionMode() : int
    {
        return $this->actionType;
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

    public function getServiceId()
    {
        return $this->serviceId;
    }
}