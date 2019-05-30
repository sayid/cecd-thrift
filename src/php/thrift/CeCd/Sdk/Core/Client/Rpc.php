<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:41
 */

namespace Thrift\CeCd\Sdk\Core\Client;

use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Exception\TException;
use Thrift\CeCd\Sdk\RpcServiceClient;
use Thrift\CeCd\Sdk\Core\RpcArrayException;

class Rpc
{
    private $rpcClass;

    private $rpcModule;

    public function __construct($classname,RpcModuleIf $rpcModule)
    {
        $this->rpcClass = $classname;
        $this->rpcModule = $rpcModule;
    }

    public function callRpc($classname, \ReflectionMethod $method, array $args = [])
    {
        $host = $this->rpcModule->getHost();
        $port = $this->rpcModule->getPort();
        $extraData = [];
        if ($this->rpcModule->getClientInterceptor()) {
            //如果注册了拦截器
            $clientInterceptorClass = $this->rpcModule->getClientInterceptor();
            $clientInterceptor = new $clientInterceptorClass;
            if (method_exists($clientInterceptor, 'before')) {
                //执行前置拦截器
                $clientInterceptor->before($extraData);
            }
        }
        $extraData['self_id'] = $this->rpcModule->getServiceId();
        $extraData['fromLang'] = "php";
        $extraData['parameterTypes'] = [];
        //调用方法有多少个参数 用于区分判断同名方法
        $extraData['parameterNum'] = $method->getNumberOfParameters();
        if ($method->getReturnType()) {
            $extraData['returnType'] = $method->getReturnType()->getName();
        }
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
                        $extraData['parameterTypes'][] = "String";
                        break;
                    case "int":
                        $extraData['parameterTypes'][] = "Integer";
                        break;
                    case "float":
                        $extraData['parameterTypes'][] = "Float";
                        break;
                    case "bool":
                        $extraData['parameterTypes'][] = "Boolean";
                        break;
                    case "double":
                        $extraData['parameterTypes'][] = "Double";
                        break;
                    case "array"://顺序数组
                        $this->extraData['parameterTypes'][] = "Array";
                        break;
                    case "object"://高级对应java的jsonobject对象，map等字典统一用这种方式
                        $extraData['parameterTypes'][] = "JSONObject";
                        break;
                    default:
                        //只能定义简单的数据类型
                        throw new \Exception($classname.":".$method." ".$parameter->getName()." type err");
                        break;
                }
            }
        }
        $extra = ceRpcEncode($extraData);
        $methodName = $method->getName();
        $args = ceRpcEncode($args);
        $RpcPools = RpcPools::getInstance();
        $i = 1;//如果发生异常 重试一次
        while ($i--) {
            try {
                //从连接池中取出
                $transport = $RpcPools->get($host, $port);
                $protocol = new TBinaryProtocol($transport);
                $client = new RpcServiceClient($protocol);
                $start = microtime_float();
                $res = $client->callRpc($classname, $methodName, $args, $extra);
                $used_time = microtime_float() - $start;
                //用完之后放回连接池中
                //$RpcPools->push($host, $port, $transport);
                $transport->close();
                if (isset($res->data)) {
                    $res->data = ceRpcDecode($res->data);
                    if (isset($clientInterceptor)
                        && method_exists($clientInterceptor, 'after')) {
                        $clientInterceptor->after($res, $classname, $methodName, $used_time);
                    }
                    return $res->data;
                } elseif ($res->code) {
                    $used_time = sprintf("%.2f", $used_time);
                    if (isset($clientInterceptor)
                        && method_exists($clientInterceptor, 'after')) {
                        $clientInterceptor->after($res, $classname, $methodName, $used_time);
                    }
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

        $res = $this->callRpc($classpath, $method, $arguments);
        return $res;
    }
}
