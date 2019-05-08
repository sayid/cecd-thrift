<?php


namespace Cecd\Sdk\Rpc\Services\MiniProgram\Models;


use Cecd\Sdk\Rpc\Services\RpcInterface;

/**
 * Interface AppModel
 * @package Cecd\Sdk\Rpc\Services\MiniProgram\Models
 * @classpath(\App\Models\AppModel)
 */
interface AppModel extends RpcInterface
{
    public function getByAppId($app_id, $company_id = 0);
}