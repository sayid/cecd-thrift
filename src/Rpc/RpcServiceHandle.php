<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/23
 * Time: 16:42
 */

namespace Ce\Sdk\Rpc;
use GouuseCore\Libraries\CodeLib;

/**
 * 服务端类
 * Class RpcServiceHandle
 * @package GouuseCore\Swoole\Rpc
 */
class RpcServiceHandle implements \Ce\Sdk\Rpc\Base\RpcServiceIf {

    public function callRpc($classname, $method, $arglist, $extra) : \Ce\Sdk\Rpc\Base\ResponseData
    {
        if (!class_exists($classname)) {
            $value = [
                "code" => CodeLib::HTTP_ERROR,
                "msg" => "rpc服务未暴露该类",
                "ex" => "class ".$classname." not found"
            ];
            return new \Ce\Sdk\Rpc\Base\ResponseData($value);
        }
        $arglist = ceRpcDecode($arglist);
        $extra = ceRpcDecode($extra);
        $reflectionClass = new \ReflectionClass($classname);
        $obj = $reflectionClass->newInstanceArgs();
        if (!$reflectionClass->hasMethod($method)) {
            $value = [
                "code" => CodeLib::HTTP_ERROR,
                "msg" => "rpc服务未暴露此方法",
                "ex" => $classname.".".$method." not found"
            ];
            return new \Ce\Sdk\Rpc\Base\ResponseData($value);
        } else {
            try {
                $data = call_user_func_array(array($obj, $method), $arglist);
                $value = [
                    "code" => 0,
                    "data" => ceRpcEncode($data),
                    "msg" => "success"
                ];
                $responseData = new \Ce\Sdk\Rpc\Base\ResponseData($value);
                return $responseData;
            } catch (\Exception $e) {
                $value = [
                    "code" => CodeLib::HTTP_ERROR,
                    "ex" => $e->getMessage()
                ];
                return new \Ce\Sdk\Rpc\Base\ResponseData($value);
            }
        }
    }
}