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
    public function getHost();

    public function getPort() : int;

    public function setHost(string $host) : RpcModuleIf;

    public function setPort(int $port) : RpcModuleIf;

    public function getServiceId(): int;

    public function getConfigKey() : string;

    public function getLang() : string;
}