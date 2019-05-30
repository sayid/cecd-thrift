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
        return json_decode($data, true);
        return msgpack_unpack($data);
    }
}

if (! function_exists('ceRpcEncode')) {
    function ceRpcEncode($data)
    {
        return json_encode($data);
        return msgpack_pack($data);
    }
}

if (! function_exists('checkOpenMethod')) {
    /**
     * 检查是否暴露了该rpc方法
     */
    function checkOpenMethod($class_load, $method)
    {
        app()->configure("service");
        if (!is_file(app()->basePath("config/service.php"))) {
            //老版本兼容
            return true;
        }
        $class_load = ltrim($class_load, "\\");
        if (!class_exists($class_load) && !class_exists("\\".$class_load)) {
            return -1;
        }

        $rows = config("service.rpc");
        if (!isset($rows[$class_load])) {
            return -2;
        } else {
            if ($rows[$class_load] == "*") {
                return true;
            } elseif (is_array($rows[$class_load]) && !isset($rows[$class_load][$method])) {
                return -3;
            }
        }
        if (!method_exists($class_load, $method)) {
            return -4;
        }
        $method = new \ReflectionMethod($class_load, $method);
        if ($method->isPublic() && !$method->isStatic()) {
            return true;
        }
        return -3;
    }
}


if (! function_exists('microtime_float')) {
    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}