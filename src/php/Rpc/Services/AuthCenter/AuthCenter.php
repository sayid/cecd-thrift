<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 12:00
 */
namespace Cecd\Sdk\Rpc\Services\AuthCenter;
use Cecd\Sdk\Rpc\Services\RpcModuleIf;
use Cecd\Sdk\Rpc\Services\RpcModuleTrait;

/**
 * Class AuthCenter
 * @package Cecd\Sdk\Rpc\Services\AuthCenter
 * @property \Cecd\Sdk\Rpc\Services\AuthCenter\Libraries\AccountLib AccountLib
 */
class AuthCenter implements RpcModuleIf
{
    use RpcModuleTrait;

    private $serviceId = 1006;

    private $config_key = 'rpc.auth-center';
}
