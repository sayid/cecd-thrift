<?php


namespace Cecd\Sdk\Rpc\Product\Services;

use Thrift\CeCd\Sdk\Core\Services\RpcInterface;


/**
 * Interface CategoryServiceImpl
 * @package Cecd\Sdk\Rpc\Product\Services
 * @classpath(com.cecd.project.product.service.CategoryServiceImpl)
 */
#[ServerClass(com.cecd.project.product.service.CategoryServiceImpl)]
interface CategoryServiceImpl extends RpcInterface
{
    public function getList(int $offset, int $limit) : array;

    /**
     * @param object $arg
     * @return array
     */
    #[ServerArgType(object)]
    public function testMap(object $arg) : object;
}
