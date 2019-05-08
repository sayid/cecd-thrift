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
        if (!class_exists($class_load)) {
            return false;
        }
        $class_load = ltrim($class_load, "\\");
        $rows = config("service.rpc");
        if (!isset($rows[$class_load])) {
            return false;
        } else {
            if ($rows[$class_load] == "*") {
                return true;
            } elseif (is_array($rows[$class_load]) && !isset($rows[$class_load][$method])) {
                return false;
            }
        }
        if (!method_exists($class_load, $method)) {
            return false;
        }
        $method = new \ReflectionMethod($class_load, $method);
        if ($method->isPublic() && !$method->isStatic()) {
            return true;
        }
        return false;
    }
}

/**
 * 读取rpc目录下的所有类
 */
/**
 * 读取rpc目录下的所有类
 */
if (! function_exists('getRpcClasses')) {
    function getRpcClasses($path, $prefix = "App\\Rpc")
    {
        $allClasses = [];
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    getRpcClasses($path . "/" . $filename, $prefix."\\".$filename);
                } else {
                    $filename = str_replace(".php", "", $filename);
                    $allClasses[] = $prefix . "\\" . $filename;
                }
            }
        }
        @closedir($handler);
        return $allClasses;
    }
}