<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:39
 */

namespace Cecd\Sdk\Rpc\Services\AuthCenter\Libraries;

use Cecd\Sdk\Rpc\Services\RpcInterface;

/**
 * Interface AccountLib
 * @package Lumen\Thrift\Rpc\Modules\AuthCenter\Libraries
 * @classpath(\App\Libraries\AccountLib)
 */
interface AccountLib extends RpcInterface
{
    public function check(string  $token) : array ;
}