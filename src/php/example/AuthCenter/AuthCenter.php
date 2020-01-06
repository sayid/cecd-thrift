<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 12:00
 */
namespace Cecd\Sdk\Rpc\AuthCenter;

use Cecd\Sdk\Rpc\RpcModuleTrait;
use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;

class AuthCenter implements RpcModuleIf
{
    use RpcModuleTrait;

    private $serviceId = 1006;

    private $config_key = 'rpc.auth-center';

    public \Cecd\Sdk\Rpc\AuthCenter\Libraries\AccountLib $AccountLib;
}
