<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/23
 * Time: 16:42
 */

namespace Cecd\Sdk\Rpc;

/**
 * 服务端类
 * Class RpcServiceHandle
 * @package GouuseCore\Swoole\Rpc
 */
class RpcServiceHandle extends \Thrift\CeCd\Sdk\Core\Command\RpcServiceHandle {

    /**
     * 调用方法先 前置处理一些逻辑
     * @param $classObject
     * @param string $method
     * @param array $arglist
     * @param array $extra
     */
    protected function before(& $classObject, string $method, array $arglist, array $extra)
    {
        getGouuseCore()->member_info = $member_info =  $extra['member_info'] ?? [];
        getGouuseCore()->company_info = $company_info =  $extra['company_info'] ?? [];
        getGouuseCore()->AuthLib->setMember($member_info);
        getGouuseCore()->AuthLib->setCompany($company_info);
        $log_data = [
            'args' => $arglist,
            'extra' => $extra
        ];
        getGouuseCore()->LogLib->info(get_class($classObject).'->'.$method, $log_data, false, 'rpc');

    }

    /**
     * 调用后，后置处理一些逻辑
     * @param $classObject
     * @param string $method
     * @param array $arglist
     * @param array $extra
     */
    protected function after(array & $returnValue )
    {
        $returnValue['debug'] = ceRpcEncode(getGouuseCore()->LogLib->optimization_list);
    }
}
