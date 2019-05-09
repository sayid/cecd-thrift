<?php


namespace Thrift\CeCd\Sdk\Core;


class RpcArrayException extends \Exception
{
    public function __construct(array $message = [], \Exception $previous = null, $code = 0)
    {
        parent::__construct(json_encode($message), $code, $previous);
    }
}