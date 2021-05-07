<?php
/**
 * rpc连接池
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/3/4
 * Time: 16:27
 */

namespace Thrift\CeCd\Sdk;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

class RpcPools
{
    protected $pool;

    protected static $PoolInstance;

    function __construct()
    {
        $this->pool = new \SplQueue;
    }

    public function getInstance()
    {
        if (empty(self::$PoolInstance)) {
            self::$PoolInstance = new self;
        }
    }
    function put($client)
    {
        self::$PoolInstance->push($client);
    }

    function get(array $config = null)
    {
        //有空闲连接
        if (count($this->pool) > 0)
        {
            return $this->pool->pop();
        }
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler); // Wrap w/ middleware
        $config['handler'] = $stack;
        return new GuzzleClient($config);
    }

    /*
     *
     * 销毁连接池
     */
    public function __destruct()
    {
        unset($this->pool);
    }
}