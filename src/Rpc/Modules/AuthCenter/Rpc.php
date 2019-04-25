<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/25
 * Time: 12:00
 */
namespace Ce\Sdk\Rpc\Modules\AuthCenter;
class Rpc
{
    private $host;

    private $port = "8090";

    /**
     * @return mixed
     */
    public function getHost()
    {
        if (!empty($this->host)) {
            return $this->host;
        } else {
            $host = env("RPC_HOST");
            return $host;
        }

    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }
}
