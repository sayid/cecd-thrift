<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/30
 * Time: 15:21
 */

namespace Thrift\CeCd\Sdk\Core\Command;

use Thrift\CeCd\Sdk\Core\Client\Rpc;
use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;

trait RpcModuleTrait
{
    private $host;

    private $port;

    private $rpcObj;

    private $clientInterceptor;//拦截器


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
        $class_load = $this->getClass($class);
        $rpc = new Rpc($class_load, $this);
        $this->rpcObj =  $rpc;
        return $this->rpcObj;
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
    public function getPort() : int
    {
        if (!empty($this->port)) {
            return $this->port;
        } else {
            $host = GEnv($this->config_key.".port");
            return $host;
        }
    }

    public function setHost(string $host) : RpcModuleIf
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port) : RpcModuleIf
    {
        $this->port = $port;
        return $this;
    }

    public function getServiceId() : int
    {
        return $this->serviceId;
    }

    public function getLang() : string
    {
        return $this->lang;
    }

    public function getConfigKey() : string
    {
        return $this->config_key;
    }

    public function getClass($property)
    {
        $class = get_class($this);
        static $propertys = [];
        if (!isset($propertys[$property])) {
            $reflectionClass = new \ReflectionClass($class);
            $doc = $reflectionClass->getDocComment();
            $array = explode("*", $doc);
            foreach ($array as $value) {
                $value = str_replace(["\n", "\r"], "", $value);
                if (($pos = strpos($value, "@property")) !== false) {
                    $string = trim(substr($value, $pos +9,  strpos($value, "$") - $pos - 9));
                    $row = trim(substr($value, strpos($value, "$") + 1));
                    $propertys[$row] = $string;
                }
            }
        }
        if (isset($propertys[$property])) {
            return $propertys[$property];
        }
        throw new \Exception($class. "'s property ".$property." is not found");
    }

}