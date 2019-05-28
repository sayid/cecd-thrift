<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:41
 */

namespace Thrift\CeCd\Sdk\Core\Client;

use mysql_xdevapi\Exception;
use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TFramedTransport;
use Thrift\Exception\TException;
use Thrift\CeCd\Sdk\RpcServiceClient;
use Thrift\CeCd\Sdk\Core\RpcArrayException;

class Rpc
{
    private $rpcClass;

    private $rpcModule;

    private $extraData;

    private static $pools = [];



    public function __construct($classname,RpcModuleIf $rpcModule)
    {
        $this->rpcClass = $classname;
        $this->rpcModule = $rpcModule;
    }

    public function setExtraData(array $extraData = [])
    {
        $this->extraData = $extraData;
    }

    public function callRpc($classname, \ReflectionMethod $method, array $args = [])
    {
        $host = $this->rpcModule->getHost();
        $port = $this->rpcModule->getPort();
        $this->extraData['self_id'] = $this->rpcModule->getServiceId();
        $this->extraData['parameterTypes'] = [];
        //调用方法有多少个参数 用于区分判断同名方法
        $this->extraData['parameterNum'] = $method->getNumberOfParameters();
        if ($this->rpcModule->getLang() == "java") {
            //如果服务器是java
            $parameters = $method->getParameters();
            foreach ($parameters as $parameter) {
                if ($parameter->hasType() == false) {
                    //参数没有类型? java说这是耍流氓
                    throw new \Exception($classname.":".$method." parameter ".$parameter->getName()." must define type");
                }
                switch ($parameter->getType()->getName()) {
                    case "string":
                        $this->extraData['parameterTypes'][] = "String";
                        break;
                    case "int":
                        $this->extraData['parameterTypes'][] = "Integer";
                        break;
                    case "float":
                        $this->extraData['parameterTypes'][] = "Float";
                        break;
                    case "bool":
                        $this->extraData['parameterTypes'][] = "Boolean";
                        break;
                    case "double":
                        $this->extraData['parameterTypes'][] = "Double";
                        break;
                    case "array"://对应java的list
                        $this->extraData['parameterTypes'][] = "Array";
                        break;
                    default:
                        //只能定义简单的数据类型
                        throw new \Exception($classname.":".$method." ".$parameter->getName()." type err");
                        break;
                }
            }
        }
        $extra = ceRpcEncode($this->extraData);

        $args = ceRpcEncode($args);
        $RpcPools = RpcPools::getInstance();
        $i = 1;//如果发生异常 重试一次
        while ($i--) {
            try {
                //从连接池中取出
                $transport = $RpcPools->get($host, $port);
                $protocol = new TBinaryProtocol($transport);
                $client = new RpcServiceClient($protocol);
                $res = $client->callRpc($classname, $method, $args, $extra);
                //用完之后放回连接池中
                //$RpcPools->push($host, $port, $transport);
                $transport->close();
                if (isset($res->data)) {
                    $res->data = ceRpcDecode($res->data);
                    return $res->data;
                } elseif ($res->code) {
                    throw new RpcArrayException(['code' => $res->code, 'msg' => $res->msg , 'exception' => $res->ex, "strace" => $res->strace]);
                }
            } catch (TException $tx) {
                if ($i == 1) {
                    continue;
                }
                throw $tx;
            } finally {
                if (isset($transport) && is_object($transport)) {
                    $transport->close();
                }
            }
        }
    }

    public function __call($name, $arguments)
    {
        $method = null;
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
            foreach ($reflectionClass->getMethods() as $methodRow) {
                app()->offsetSet($this->rpcClass.$methodRow->getName(), $methodRow);
                if ($methodRow->getName() == $name) {
                    $method = $methodRow;
                }
            }
            if (empty($method)) {
                throw new \Exception($this->rpcClass." method ". $name . " not found");
            }
        } else {
            $classpath = app()->offsetGet($this->rpcClass);
            $method = app()->offsetGet($this->rpcClass.$name);
        }
        if (env('APP_DEBUG') == true && function_exists('getGouuseCore')) {
            getGouuseCore()->LogLib->rpc_count += 1;
        }
        $start = microtime_float();
        $res = $this->callRpc($classpath, $method, $arguments);
        if (function_exists('getGouuseCore')) {
            getGouuseCore()->LogLib->optimization_list[] = microtime_float() - $start . "ms rpc:" . $classpath . "->" . $name . "()";
        }
        return $res;
    }

    /**
     * 检查数组是array还是hash表
     * @param array $array
     */
    private function isMapArray(array $array) {
        $lastIndex = 0;
        foreach ($array as $index => $value) {
            if ($index !== $lastIndex) {
                return false;
            }
            $lastIndex++;
        }
        return true;
    }
}