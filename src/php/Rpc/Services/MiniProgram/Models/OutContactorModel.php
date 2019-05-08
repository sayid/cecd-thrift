<?php


namespace Cecd\Sdk\Rpc\Services\MiniProgram\Models;


use Cecd\Sdk\Rpc\Services\RpcInterface;

/**
 * Interface AppModel
 * @package Cecd\Sdk\Rpc\Services\MiniProgram\Models
 * @classpath(\App\Models\OutContactorModel)
 */
interface OutContactorModel extends RpcInterface
{
    public function getOne(string $field = "",array $where = []);
}