<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:39
 */

namespace Ce\Sdk\Rpc\Modules\AuthCenter\Libraries;

/**
 * Interface AccountLib
 * @package Ce\Sdk\Rpc\Modules\AuthCenter\Libraries
 * @classpath(\App\Libraries\AccountLib)
 */
interface AccountLib extends \Ce\Sdk\Rpc\Modules\RpcInterface
{
    public function check(string  $token) : array ;
}