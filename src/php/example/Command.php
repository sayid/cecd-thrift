<?php
namespace Cecd\Sdk\Rpc;

use Thrift\CeCd\Sdk\Core\TSwooleServerTransport;
use Thrift\CeCd\Sdk\RpcServiceProcessor;
use Thrift\Factory\TTransportFactory;
use Thrift\Factory\TBinaryProtocolFactory;

/**
 * Class Command.
 */
class Command extends \Thrift\CeCd\Sdk\Core\Command\Command
{

    /**
     * Run up the server.
     *
     * @param string $argv
     *
     * @throws \Exception
     */
    public function run($argv)
    {
        require ROOT_PATH .'vendor/autoload.php';

        try {
            InitEnv(ROOT_PATH);
            $this->serverOptions['pid_file'] = '/var/tmp/server_rpc_'.env("SERVICE_ID").'.pid';
        } catch (\Dotenv\Exception\InvalidPathException $e) {
            throw $e;
        }

        if (env("SERVICE_ID") != 1036 && $argv[1] != 'stop') {
            ReloadGEnv(ROOT_PATH);
        }

        if ($this->handleAction($argv)) {
            return;
        }
        if (!$this->handleArguments()) {
            return;
        }


        if (file_exists($this->serverOptions['pid_file'])) {
            $pid = file_get_contents($this->serverOptions['pid_file']);
            if (posix_getpgid($pid)) {
                echo "server is running\n";
                return;
            } else {
                //意外退出后再次启动
                unlink($this->serverOptions['pid_file']);
            }
        }
        $this->serverOptions['worker_num'] = env("SWOOLE_WORKER_NUM", 4);
        $this->serverOptions['log_file'] = ROOT_PATH .'storage/logs/swoole.log';
        $handler = new RpcServiceHandle();
        $processor = new RpcServiceProcessor($handler);

        $socket_tranport = new TSwooleServerTransport($this->host, $this->port);
        $out_factory = $in_factory = new TTransportFactory();
        $out_protocol = $in_protocol = new TBinaryProtocolFactory();
        $server = new SwooleServer($processor, $socket_tranport, $in_factory, $out_factory, $in_protocol, $out_protocol);
        $server->options($this->serverOptions);
        $server->setServer($this->host, $this->port);
        $server->serve();
    }
}
