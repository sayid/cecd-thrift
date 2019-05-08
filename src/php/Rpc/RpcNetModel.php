<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/22
 * Time: 17:32
 */

namespace GouuseCore\Rpcs;


class RpcModel
{
    private $className; //类名

    private $method; //方法名

    private $args = []; //参数

    private $serviceId;

    private $fromUrl;

    private $requestId;

    private $requestIp;

    private $systemType;

    private $rpcVersion = 20190422;//rpc版本

    private $cacheTime = 0;

    private $registerKeys = [];

    private $rpcFolder;

    /**
     * @return int
     */
    public function getCacheTime()
    {
        return $this->cacheTime;
    }

    /**
     * @param int $cacheTime
     */
    public function setCacheTime($cacheTime)
    {
        $this->cacheTime = $cacheTime;
    }

    /**
     * @return array
     */
    public function getRegisterKeys()
    {
        return $this->registerKeys;
    }

    /**
     * @param array $registerKeys
     */
    public function setRegisterKeys($registerKeys)
    {
        $this->registerKeys = $registerKeys;
    }

    /**
     * @return mixed
     */
    public function getRpcFolder()
    {
        return $this->rpcFolder;
    }

    /**
     * @param mixed $rpcFolder
     */
    public function setRpcFolder($rpcFolder)
    {
        $this->rpcFolder = $rpcFolder;
    }



    /**
     * @return int
     */
    public function getRpcVersion()
    {
        return $this->rpcVersion;
    }

    /**
     * @param int $rpcVersion
     */
    public function setRpcVersion($rpcVersion)
    {
        $this->rpcVersion = $rpcVersion;
    }

    public  function getClassName() {
        return $this->className;
    }

    public function setClassName($className) {
        $this->className = $className;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function getArgs() {
        return $this->args;
    }

    public function setArgs($args) {
        $this->args = $args;
    }

    /**
     * @return mixed
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * @param mixed $serviceId
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    }

    /**
     * @return mixed
     */
    public function getFromUrl()
    {
        return $this->fromUrl;
    }

    /**
     * @param mixed $fromUrl
     */
    public function setFromUrl($fromUrl)
    {
        $this->fromUrl = $fromUrl;
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * @return mixed
     */
    public function getRequestIp()
    {
        return $this->requestIp;
    }

    /**
     * @param mixed $requestIp
     */
    public function setRequestIp($requestIp)
    {
        $this->requestIp = $requestIp;
    }

    /**
     * @return mixed
     */
    public function getSystemType()
    {
        return $this->systemType;
    }

    /**
     * @param mixed $systemType
     */
    public function setSystemType($systemType)
    {
        $this->systemType = $systemType;
    }


    public function toString() {
        return "RpcModel [className=" . $this->className . ", method=" . $this->method . ", args=" . print_r($this->args, true) ."]";
    }
}