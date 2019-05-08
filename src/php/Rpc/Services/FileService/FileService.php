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

class FileService implements RpcModuleIf
{
    use RpcModuleTrait;

    private $serviceId = 1000;

    private $config_key = 'rpc.file-service';
}
