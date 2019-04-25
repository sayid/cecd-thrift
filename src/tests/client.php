<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 12:06
 */

use Ce\Sdk\Rpc\Modules\AuthCenter\Libraries\AccountLib;
use \Ce\Sdk\Rpc\RpcFactory;


$accountLib = RpcFactory::getRpc(AccountLib::class);
$result = $accountLib->check("123");

