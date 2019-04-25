<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:33
 */

if (! function_exists('ceRpcDecode')) {
    function ceRpcDecode($data)
    {
        return msgpack_unpack($data);
    }
}

if (! function_exists('ceRpcEncode')) {
    function ceRpcEncode($data)
    {
        return msgpack_pack($data);
    }
}