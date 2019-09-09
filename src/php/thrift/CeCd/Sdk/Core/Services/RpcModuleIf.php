<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/30
 * Time: 15:21
 */

namespace Thrift\CeCd\Sdk\Core\Services;


interface RpcModuleIf
{
    public function getHost() : string;

    public function getPort() : int;

    public function setHost(string $host) : RpcModuleIf;

    public function setPort(int $port) : RpcModuleIf;

    public function getServiceId(): int;

    public function getConfigKey() : string;

    public function getLang() : string;

    public function getClientInterceptor() : string;

    public function setMode(int $actionType) : RpcModuleIf;

    public function getActionMode() : int;

    public function setDebug(bool $isDebug = false) : RpcModuleIf;

    public function getDebug() : bool;

}