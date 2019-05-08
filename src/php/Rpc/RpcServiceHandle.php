<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/23
 * Time: 16:42
 */

namespace Cecd\Sdk\Rpc;
use GouuseCore\Libraries\CodeLib;

use Thrift\CeCd\Sdk\RpcServiceIf;
use Thrift\CeCd\Sdk\ResponseData;
use GouuseCore\Helpers\RpcHelper;

/**
 * 服务端类
 * Class RpcServiceHandle
 * @package GouuseCore\Swoole\Rpc
 */
class RpcServiceHandle implements RpcServiceIf {

    private $afterCall;//执行完成后要执行的函数

    public function callRpc($classname, $method, $arglist, $extra) : ResponseData
    {

        if (!class_exists($classname)) {
            $value = [
                "code" => CodeLib::HTTP_ERROR,
                "msg" => "rpc服务未暴露该类",
                "ex" => "class ".$classname." not found"
            ];
            return new ResponseData($value);
        }
        $arglist = ceRpcDecode($arglist);
        $extra = ceRpcDecode($extra);

        if (!($obj = checkOpenMethod($classname, $method))) {
            $value = [
                'code' => 1000,
                "msg" => "",
                'ex' => env("SERVICE_ID") . ":" . $classname."->".$method." not exists",
            ];
            return new ResponseData($value);
        }

        $reflectionClass = new \ReflectionClass($classname);
        $obj = $reflectionClass->newInstanceArgs();

        try {
            $start = microtime_float();
            $data = call_user_func_array(array($obj, $method), $arglist);
            $runtime = microtime_float() - $start;
            //执行注册的回调函数，可以做一些资源回收，如事务回滚
            //注销容器 单例

            //$this->doAfterCall();
            $value = [
                "code" => 0,
                "data" => ceRpcEncode($data),
                "msg" => "success",
                "debug" => ceRpcEncode(getGouuseCore()->LogLib->optimization_list),
                "runtime" => $runtime
            ];
            getGouuse()->distroy();
            $responseData = new ResponseData($value);
            return $responseData;
        } catch (\Exception $e) {
            $value = [
                "code" => CodeLib::HTTP_ERROR,
                "ex" => $e->getMessage()
            ];
            return new ResponseData($value);
        }

    }

    public function registryAfterCall(\Closure $clourse)
    {
        $this->afterCall = $clourse;
    }

    private function doAfterCall()
    {
        if ($this->afterCall) {
            call_user_func($this->afterCall);
        }

    }
}