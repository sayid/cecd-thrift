<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:53
 */

namespace Ce\Sdk\Rpc;


class RpcFactory
{
    public static function  getRpc($classname) : \Ce\Sdk\Rpc\Modules\RpcInterface
    {
        $proxy = new \Ce\Sdk\Rpc\Client\Rpc($classname);
        return $proxy;
    }
}