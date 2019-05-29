<?php
namespace Cecd\Sdk\Rpc;

use Thrift\Server\TServer;
use GouuseCore\Helpers\OptionHelper;
/**
 * Class Server.
 */
class SwooleServer  extends \Thrift\CeCd\Sdk\Core\Command\SwooleServer {

    /**
     * 资源回收
     */
    public function onClose()
    {
        getGouuseCore()->distroy();
        OptionHelper::distroyGouuse();//注销容器
    }

    public function handleLumenShutdown()
    {
        if ($error = error_get_last()) {
            getGouuseCore()->LogLib->error($error['message'], $error);
        } else {
            getGouuseCore()->LogLib->error("rpc swoole error");
        }
    }
}