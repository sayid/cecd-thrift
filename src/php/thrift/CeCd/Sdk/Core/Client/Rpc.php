<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:41
 */

namespace Thrift\CeCd\Sdk\Core\Client;


use Cecd\Sdk\Rpc\Services\RpcModuleIf;
use GouuseCore\Exceptions\GouuseRpcException;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TFramedTransport;
use Thrift\Exception\TException;
use Cecd\Sdk\Rpc\Services\RpcInterface;
use Thrift\CeCd\Sdk\RpcServiceClient;

class Rpc implements RpcInterface
{
    private $rpcClass;

    private $rpcModule;

    private static $pools = [];



    public function __construct($classname, RpcModuleIf $rpcModule)
    {
        $this->rpcClass = $classname;
        $this->rpcModule = $rpcModule;
    }

    public function callRpc($classname, $method, $args = [])
    {
        $host = $this->rpcModule->getHost();
        $port = $this->rpcModule->getPort();
        $extra = ceRpcEncode([]);
        $args = ceRpcEncode($args);
        $RpcPools = RpcPools::getInstance();
        $i = 2;//如果发生异常 重试一次
        while ($i--) {
            try {
                //从连接池中取出
                $transport = $RpcPools->get($host, $port);
                $protocol = new TBinaryProtocol($transport);
                $client = new RpcServiceClient($protocol);
                $res = $client->callRpc($classname, $method, $args, $extra);
                //用完之后放回连接池中
                $RpcPools->push($host, $port, $transport);
                //$transport->close();
                if (isset($res->data)) {
                    $res->data = ceRpcDecode($res->data);
                    return $res->data;
                } elseif ($res->code) {
                    throw new GouuseException(['code' => $res->code, 'msg' => $res->msg , 'exception' => $res->ex]);
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

    /**
     * 放入连接池
     * @param $host
     * @param $port
     * @param $transport
     */
    private function push($host, $port, $transport)
    {
        if (!isset(self::$pools[md5($host.$port)])) {
            self::$pools[md5($host . $port)] = new \SplQueue();
        }
        self::$pools[md5($host . $port)]->push($transport);
    }

    private function get($host, $port)
    {
        if (!isset(self::$pools[md5($host.$port)])) {
            self::$pools[md5($host . $port)] = new \SplQueue();
        } else {
            $transport = self::$pools[md5($host . $port)]->get();
        }
        if (empty($transport)) {
            $socket = new TSocket($host, $port);
            $transport = new TFramedTransport($socket);
        }
        return $transport;
    }

    public function __destruct()
    {
       foreach (self::$pools as $pools) {
            foreach ($pools as $transport) {
                $transport->close();
            }
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
        $start = microtime_float();
        $res = $this->callRpc($classpath, $name, $arguments);
        getGouuseCore()->LogLib->optimization_list[] = microtime_float() - $start . "ms rpc:".$classpath."->".$name."()";
        return $res;
    }
}