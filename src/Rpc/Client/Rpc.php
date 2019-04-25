<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:41
 */

namespace Ce\Sdk\Rpc\Client;


use GouuseCore\Exceptions\GouuseRpcException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TFramedTransport;
use Thrift\Exception\TException;

class Rpc implements \Ce\Sdk\Rpc\Modules\RpcInterface
{
    private $rpcClass;

    public function __construct($classname)
    {
        $this->rpcClass = $classname;
    }

    public function callRpc($host, $port, $classname, $method, $args = [])
    {
        try {
            $socket = new TSocket($host, $port);

            $transport = new TFramedTransport($socket);
            $protocol = new TBinaryProtocol($transport);
            $transport->open();
            $client = new \Ce\Sdk\Rpc\Base\RpcServiceClient($protocol);
            $extra = ceRpcEncode([]);
            $args = ceRpcEncode($args);
            $res = $client->callRpc($classname, $method, $args, $extra);
            $transport->close();
            if (isset($res->data)) {
                $res->data = ceRpcDecode($res->data);
            } elseif($res->code) {
                throw new GouuseRpcException($res->ex);
            }
            return $res;
        } catch (TException $tx) {
            throw $tx;
        }
    }

    public function __call($name, $arguments)
    {
        if (!app()->offsetExists($this->rpcClass)) {
            $reflectionClass = new \ReflectionClass($this->rpcClass);

            $comment = $reflectionClass->getDocComment();
            $pos_start = strpos($comment, "@classpath(");
            if (!$pos_start) {
                throw \Exception("请设置rpc类中的classpath");
            }
            $pos_start += 11;
            $pos_end = strpos($comment, ")", $pos_start);
            if (!$pos_end) {
                throw \Exception("请设置rpc类中的classpath");
            }
            $classpath = substr($comment, $pos_start, $pos_end - $pos_start);
            app()->offsetSet($this->rpcClass, $classpath);
        } else {
            $classpath = app()->offsetGet($this->rpcClass);
        }
        $pos = strpos($this->rpcClass, "\\", 19);
        $config = substr($this->rpcClass, 0, $pos)."\Rpc";
        $configObj = new $config;
        $host = $configObj->getHost();
        $port = $configObj->getPort();
        return $this->callRpc($host, $port, $classpath, $name, $arguments);
    }
}