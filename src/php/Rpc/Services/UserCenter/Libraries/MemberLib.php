<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:39
 */

namespace Cecd\Sdk\Rpc\Services\UserCenter\Libraries;

use Cecd\Sdk\Rpc\Services\RpcInterface;

/**
 * Interface MemberLib
 * @package Lumen\Thrift\Rpc\Modules\UserCenter\Libraries
 * @classpath(\App\Libraries\MemberLib)
 */
interface MemberLib extends RpcInterface
{
    public function getSimpleMemberById(int  $member_id) : array ;
}