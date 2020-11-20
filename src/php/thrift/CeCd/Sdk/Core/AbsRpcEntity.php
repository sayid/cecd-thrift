<?php


namespace Thrift\CeCd\Sdk\Core;

/**
 * rpc实体基类
 * Class AbsRpcEntity
 * @package Thrift\CeCd\Sdk\Core
 */
abstract class AbsRpcEntity implements \JsonSerializable
{
    // 实现的抽象类方法，指定需要被序列化JSON的数据
    public function jsonSerialize() {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
}
