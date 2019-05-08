<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 12:00
 */
namespace Cecd\Sdk\Rpc\Services\MiniProgram;
use Cecd\Sdk\Rpc\Services\RpcModuleIf;
use Cecd\Sdk\Rpc\Services\RpcModuleTrait;

/*
 *  @property \Cecd\Sdk\Rpc\Services\MiniProgram\Models\AppModel AppModel
 *  @property \Cecd\Sdk\Rpc\Services\MiniProgram\Models\BusinessCardModel BusinessCardModel
 *  @property \Cecd\Sdk\Rpc\Services\MiniProgram\Models\OutContactorModel OutContactorModel
 */
class MiniProgram implements RpcModuleIf
{
    use RpcModuleTrait;

    private $serviceId = 1033;

    private $config_key = 'rpc.miniprogram';
}
