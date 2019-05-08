<?php

namespace Cecd\Sdk\Rpc\Services\MiniProgram\Models;

use Cecd\Sdk\Rpc\Services\RpcInterface;

/**
 * Interface BusinessCardModel
 * @package Cecd\Sdk\Rpc\Services\MiniProgram\Models
 * @classpath(\App\Models\BusinessCardModel)
 */
interface BusinessCardModel extends RpcInterface
{

    /**
     * 传入基础数据初始化名片
     * @param $field
     * @return bool|mixed|string
     */
    public function initCard(array $field);

    public function getOne(string $field = "",array $where = []);

    public function getSelectAll(string $field = "",array $where = [],int $offset = 0,int $page = 10,string $order = "");
}
