<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 12:00
 */
namespace Cecd\Sdk\Rpc\AuthCenter;

use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;
use Cecd\Sdk\Rpc\RpcModuleTrait;

/**
 * Class AuthCenter
 * @package Cecd\Sdk\Rpc\AuthCenter
 * @property \Cecd\Sdk\Rpc\AuthCenter\Libraries\AccountLib $AccountLib
 */
class AuthCenter implements RpcModuleIf
{
    use RpcModuleTrait;

    private $serviceId = 1006;

    private $config_key = 'rpc.auth-center';
}
