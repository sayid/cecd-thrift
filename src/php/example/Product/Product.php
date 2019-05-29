<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 12:00
 */
namespace Cecd\Sdk\Rpc\Product;

use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;
use Cecd\Sdk\Rpc\RpcModuleTrait;

/**
 * Class Product
 * @package Cecd\Sdk\Rpc\Product
 * @property \Cecd\Sdk\Rpc\Product\Services\CategoryServiceImpl $CategoryServiceImpl
 */
class Product implements RpcModuleIf
{
    use RpcModuleTrait;

    private $serviceId = 2002;

    private $config_key = 'rpc.product';

    private $lang = "java";
}
