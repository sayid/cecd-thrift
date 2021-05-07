<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 11:50
 */

namespace Thrift\CeCd\Sdk\Core\Services;

/**
 * 定义rpc基础接口，所有rpc类都要继承
 * Interface RpcInterface
 * @package Ce\Sdk\Rpc\Modules
 */
interface RpcModelIf extends  RpcInterface
{
    public function getOne($field = "", $where);

    public function getSelectAll($field = "", $where = [], $offset = 0, $page = 10, $order = "");
}