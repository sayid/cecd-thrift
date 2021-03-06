<?php
namespace Thrift\CeCd\Sdk\Core\Command;
use Error;
use ErrorException;
use Laravel\Lumen\Exceptions\Handler;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

use Thrift\CeCd\Sdk\Core\TSwooleServerTransport;
use Thrift\CeCd\Sdk\RpcServiceProcessor;
use Thrift\Factory\TTransportFactory;
use Thrift\Factory\TBinaryProtocolFactory;

/**
 * Class Command.
 */
abstract class Command
{
    /**
     * Pid file.
     *
     * @var string
     */
    protected $pidFile;
    /**
     * Command options.
     *
     * @var array
     */
    protected $options = [];
    /**
     * Server host.
     *
     * @var string
     */
    protected $host = '127.0.0.1';
    /**
     * Server port.
     *
     * @var int
     */
    protected $port = 8083;
    /**
     * Application bootstrap file.
     *
     * @var string
     */
    protected $bootstrap = 'bootstrap/app.php';

    /**
     * Http server options.
     *
     * @var array
     */
    protected $serverOptions = [
        'worker_num' => 5,
        //'dispatch_mode' => 3, //抢占模式，主进程会根据Worker的忙闲状态选择投递，只会投递给处于闲置状态的Worker
        'max_request' => 2000,
        'buffer_output_size' => 15728640, //一次最大能输出15兆
        'enable_coroutine' => true,
        //以下为rpc的报文解析配置
        'open_length_check'     => true, //打开包长检测
        'package_max_length'    => 8192000, //最大的请求包长度,8M
        'package_length_type'   => 'N', //长度的类型，参见PHP的pack函数
        'package_length_offset' => 0,   //第N个字节是包长度的值
        'package_body_offset'   => 4,   //从第几个字节计算长度
    ];
    /**
     * Create a new Command instance.
     */
    public function __construct()
    {
        $this->registerErrorHandling();
    }
    /**
     * Main access.
     *
     * @param $argv
     *
     * @return mixed
     */
    public static function main($argv)
    {
        $command = new static();
        return $command->run($argv);
    }
    /**
     * Run up the server.
     *
     * @param string $argv
     *
     * @throws \Exception
     */
    abstract public function run($argv);
    /*{
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
    }*/
    /**
     * Handle command action.
     *
     * @param array $argv
     *
     * @return bool
     */
    public function handleAction($argv)
    {
        if (count($argv) < 2) {
            return false;
        }
        if (in_array($argv[1], ['stop', 'reload', 'restart'])) {
            call_user_func([$this, $argv[1]]);
            return true;
        }
        return false;
    }
    /**
     * Handle Command arguments.
     *
     * @return bool
     */
    public function handleArguments()
    {
        $serverOptions = array_map(function ($option) {
            return "$option:";
        }, SwooleServer::$validServerOptions);
        $longOptions = array_merge(['host:', 'port:', 'help', 'version'], $serverOptions);
        $options = getopt('dvp:h::s:', $longOptions);
        foreach ($options as $option => $value) {
            switch ($option) {
                case 'h':
                case 'host':
                    if ($value) {
                        $this->host = $value;
                    } else {
                        return false;
                    }
                    break;
                case 'p':
                case 'port':
                    if ($value) {
                        $this->port = (int) $value;
                    }
                    break;
                case 's':
                    if ($value) {
                        $this->bootstrap = $value;
                    }
                    break;
                case 'd':
                    $this->serverOptions['daemonize'] = true;
                    break;
                case 'v':
                default:
                    if (in_array($option, SwooleServer::$validServerOptions) && $value) {
                        $this->serverOptions[$option] = $value;
                    }
                    break;
            }
        }
        return true;
    }

    /**
     * Stop the server.
     *
     * @throws \Exception
     *
     * @return void
     */
    public function stop()
    {
        $pid = $this->getPid();
        echo "Server is stopping...\r\n";
        posix_kill($pid, SIGTERM);
        usleep(500);
        posix_kill($pid, SIGKILL);
        unlink($this->pidFile);
    }
    /**
     * Reload the server.
     *
     * @throws \Exception
     *
     * @return void
     */
    public function reload()
    {
        posix_kill($this->getPid(), SIGUSR1);
    }


    /**
     * Restart the server.
     *
     * @throws \Exception
     *
     * @return void
     */
    public function restart()
    {
        $pid = $this->getPid();
        $cmd = exec("ps -p $pid -o args | grep lumen-swoole");
        if (empty($cmd)) {
            throw new \Exception('Cannot find server process.');
        }
        $this->stop();
        usleep(2000);
        echo "Server is starting...\r\n";
        exec($cmd);
    }
    /**
     * Get process identifier of this server.
     *
     * @throws \Exception
     *
     * @return string|false
     */
    protected function getPid()
    {
        $this->pidFile = $this->serverOptions['pid_file'];//substr(__DIR__,0, -30 ) . ('/storage/lumen-swoole.pid');
        if (!file_exists($this->pidFile)) {
            throw new \Exception('The Server is not running.');
        }
        $pid = file_get_contents($this->pidFile);
        if (posix_getpgid($pid)) {
            return $pid;
        }
        unlink($this->pidFile);
        return false;
    }
    /**
     * Set the error handling for the application.
     *
     * @return void
     */
    protected function registerErrorHandling()
    {
        //错误级别
        $error_reporting = env("ERROR_REPORTING", -1);
        error_reporting($error_reporting);
        set_error_handler(function ($level, $message, $file = '', $line = 0) {
            if (error_reporting() & $level) {
                throw new ErrorException($message, 0, $level, $file, $line);
            }
        });
        set_exception_handler(function ($e) {
            $this->handleUncaughtException($e);
        });
        register_shutdown_function(function () {
            $this->handleShutdown();
        });
    }
    /**
     * Handle an uncaught exception instance.
     *
     * @param \Exception $e
     *
     * @return void
     */
    protected function handleUncaughtException($e)
    {
        if ($e instanceof Error) {
            $e = new FatalThrowableError($e);
        }
        (new Handler())->renderForConsole(new ConsoleOutput(), $e);
    }
    /**
     * Handle the application shutdown routine.
     *
     * @return void
     */
    protected function handleShutdown()
    {
        if (!is_null($error = error_get_last()) && $this->isFatalError($error['type'])) {
            $this->handleUncaughtException(new FatalErrorException(
                $error['message'],
                $error['type'],
                0,
                $error['file'],
                $error['line']
            ));
        }
    }
    /**
     * Determine if the error type is fatal.
     *
     * @param int $type
     *
     * @return bool
     */
    protected function isFatalError($type)
    {
        $errorCodes = [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE];
        if (defined('FATAL_ERROR')) {
            $errorCodes[] = FATAL_ERROR;
        }
        return in_array($type, $errorCodes);
    }
}