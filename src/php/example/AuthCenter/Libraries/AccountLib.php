<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:39
 */

namespace Cecd\Sdk\Rpc\AuthCenter\Libraries;

use Thrift\CeCd\Sdk\Core\Services\RpcInterface;

/**
 * Interface AccountLib
 * @package Cecd\Sdk\Rpc\AuthCenter\Libraries
 * @classpath(\App\Libraries\AccountLib)
 */
interface AccountLib extends RpcInterface
{
    public function check(string  $token) : array ;
}