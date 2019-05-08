<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 12:00
 */
namespace Cecd\Sdk\Rpc\Services\UserCenter;
use Cecd\Sdk\Rpc\Services\RpcModuleIf;
use Cecd\Sdk\Rpc\Services\RpcModuleTrait;

/**
 * Class UserCenter
 * @package Cecd\Sdk\Rpc\Services\UserCenter
 * @property \Cecd\Sdk\Rpc\Services\UserCenter\Models\MemberModel MemberModel
 * @property \Cecd\Sdk\Rpc\Services\UserCenter\Libraries\MemberLib MemberLib
 */
class UserCenter implements RpcModuleIf
{
    use RpcModuleTrait;

    private $serviceId = 1005;

    private $config_key = 'rpc.user-center';
}
