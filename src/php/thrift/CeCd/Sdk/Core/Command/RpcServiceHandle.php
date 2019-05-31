<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/23
 * Time: 16:42
 */

namespace Thrift\CeCd\Sdk\Core\Command;
use GouuseCore\Libraries\CodeLib;

use Thrift\CeCd\Sdk\RpcServiceIf;
use Thrift\CeCd\Sdk\ResponseData;

/**
 * 服务端类
 * Class RpcServiceHandle
 * @package GouuseCore\Swoole\Rpc
 */
class RpcServiceHandle implements RpcServiceIf {

    private $serverInterceptor;

    public function setServerInterceptorClass($serverInterceptorClass)
    {
        $this->serverInterceptor = $serverInterceptorClass;
    }

    public function callRpc($classname, $method, $arglist, $extra) : ResponseData
    {

        if ($classname == "ping" && $method == "ping") {
            $value = [
                "code" => 0,
                "data" => ceRpcEncode("ok"),
                "msg" => "success"
            ];
            return new ResponseData($value);
        }
        $arglist = ceRpcDecode($arglist);
        $extra = ceRpcDecode($extra);
        $client_service_id = $extra['self_id'] ?? 0;
        $fromUrl = $extra['from_url'] ?? "";
        $from_service_id = $extra['from_service_id'] ?? "";
        $form_request_id = $extra['form_request_id'] ?? "";

        /*if ($client_service_id && $client_service_id != env("SERVICE_ID")) {
            //客户端的service_id不匹配与服务器的service_id
            $value = [
                'code' => 1000,
                "msg" => "",
                'ex' => "this rpc client is not this server'client->".$client_service_id."-".env("SERVICE_ID"),
            ];
            return new ResponseData($value);
        }*/
        if (!class_exists($classname)) {
            $value = [
                'code' => 1000,
                "msg" => "",
                'ex' => env("SERVICE_ID") . ":" . $classname."->".$method." not exists",
            ];
            return new ResponseData($value);
        }
        $reflectionClass = new \ReflectionClass($classname);
        $obj = $reflectionClass->newInstanceArgs();
        if (!method_exists($obj, $method)) {
            $value = [
                'code' => 1000,
                "msg" => "",
                'ex' => env("SERVICE_ID") . ":" . $classname."->".$method." not exists",
            ];
            return new ResponseData($value);
        }
        try {
            //调用前置拦截
            if ($this->serverInterceptor && method_exists($this->serverInterceptor, "before")) {
                $before = $this->serverInterceptor->before($obj, $method, $arglist, $extra);
                if ($before instanceof ResponseData) {
                    return $before;
                }
            }

            $start = microtime_float();
            $data = call_user_func_array(array($obj, $method), $arglist);
            $runtime = microtime_float() - $start;
            $value = [
                "code" => 0,
                "data" => ceRpcEncode($data),
                "msg" => "success",
                "runtime" => $runtime
            ];
            if ($this->serverInterceptor && method_exists($this->serverInterceptor, "after")) {
                $this->serverInterceptor->after($value);
            }

            $responseData = new ResponseData($value);
            return $responseData;
        } catch (\Exception $e) {
            $value = [
                "code" => CodeLib::HTTP_ERROR,
                "ex" => $e->getMessage(),
                "strace" => $e->__toString()
            ];
            if ($this->serverInterceptor && method_exists($this->serverInterceptor, "after")) {
                $this->serverInterceptor->after($value);
            }
            return new ResponseData($value);
        } catch (\Error $e) {
            $value = [
                "code" => CodeLib::HTTP_ERROR,
                "ex" => $e->getMessage(),
                "strace" => $e->__toString()
            ];
            if ($this->serverInterceptor && method_exists($this->serverInterceptor, "after")) {
                $this->serverInterceptor->after($value);
            }
            return new ResponseData($value);
        } catch (\FatalErrorException $e) {
            $value = [
                "code" => CodeLib::HTTP_ERROR,
                "ex" => $e->getMessage(),
                "strace" => $e->__toString()
            ];
            if ($this->serverInterceptor && method_exists($this->serverInterceptor, "after")) {
                $this->serverInterceptor->after($value);
            }
            return new ResponseData($value);
        }
    }

    /**
     * 输出rpc服务信息
     * @param string|null $service
     * @return array
     * @throws \ReflectionException
     */
    public function rpcInfo(string $service, string $package = "Cecd\Sdk\Rpc\\") : array
    {
        if (empty($service)) {
            return [];
        }
        $classname = $package . "$service\\$service";
        $reflectionClass = new \ReflectionClass($classname);
        $comment = $reflectionClass->getDocComment();
        $infos = explode("* ", $comment);
        $data = [
            'service' => $classname,
            'rpcs' => []
        ];
        if (is_array($infos)) {
            foreach ($infos as $info) {
                $pos = strpos($info,"@property");
                if ($pos === false) {
                    continue;
                }
                $classinfo = ltrim(substr($info, 9));
                $row = [
                    'rpc' => $classinfo,
                    "methods" => []
                ];
                $class = substr($classinfo, 0, strpos($classinfo, " "));
                $reflectionClass = new \ReflectionClass($class);
                $methods = $reflectionClass->getMethods();
                foreach ($methods as $method) {
                    $methodInfo = [
                        'method' => $method->getName(),
                        'return' => '',
                        'args' => [],
                        'doc' => $method->getDocComment()
                    ];
                    if ($method->getReturnType()) {
                        $methodInfo['return'] = $method->getReturnType()->getName();
                    }
                    if ($method->getParameters()) {
                        foreach ($method->getParameters() as $args) {
                            $methodInfo['args'][] = ($args->hasType() ? $args->getType()." " : '') . $args->getName();
                        }
                    }
                    $row['methods'][] = $methodInfo;
                }
                $data['rpcs'][]= $row;
            }
        }
        return $data;
    }
}
