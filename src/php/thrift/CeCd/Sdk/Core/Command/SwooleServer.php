<?php
namespace Thrift\CeCd\Sdk\Core\Command;

use Thrift\Server\TServer;
use GouuseCore\Helpers\OptionHelper;
/**
 * Class Server.
 */
abstract class SwooleServer extends TServer
{
    /**
     * lumen-swoole version.
     */
    const VERSION = 'ce-swoole 0.1.0';
    /**
     * @var \Laravel\Lumen\Application
     */
    protected $app;
    /**
     * Default host.
     *
     * @var string
     */
    protected $host = '0.0.0.0';
    /**
     * Default port.
     *
     * @var int
     */
    protected $port = 8091;
    /**
     * Pid file.
     *
     * @var string
     */
    protected $pidFile = '';
    /**
     * Http server instance.
     *
     * @var HttpServer
     */
    protected $server;
    /**
     * Http server options.
     *
     * @var array
     */
    protected $options = [];
    /**
     * Application snapshot.
     *
     * @var null
     */
    protected $appSnapshot = null;

    protected $_SERVER = [];
    /**
     * Valid swoole http server options.
     *
     * @see http://wiki.swoole.com/wiki/page/274.html
     *
     * @var array
     */
    public static $validServerOptions = [
        'reactor_num',
        'worker_num',
        'max_request',
        'max_conn',
        'task_worker_num',
        'task_ipc_mode',
        'task_max_request',
        'task_tmpdir',
        'dispatch_mode',
        'message_queue_key',
        'daemonize',
        'backlog',
        'pid_file',
        'log_file',
        'log_level',
        'heartbeat_check_interval',
        'heartbeat_idle_time',
        'open_eof_check',
        'open_eof_split',
        'package_eof',
        'open_length_check',
        'package_length_type',
        'package_max_length',
        'open_cpu_affinity',
        'cpu_affinity_ignore',
        'open_tcp_nodelay',
        'tcp_defer_accept',
        'ssl_cert_file',
        'ssl_method',
        'user',
        'group',
        'chroot',
        'pipe_buffer_size',
        'buffer_output_size',
        'socket_buffer_size',
        'enable_unsafe_event',
        'discard_timeout_request',
        'enable_reuse_port',
        'ssl_ciphers',
        'enable_delay_receive',
    ];
    /**
     * If shutdown function registered.
     *
     * @var bool
     */
    protected $shutdownFunctionRegistered = false;
    /**
     * Create a new Server instance.
     *
     * @param string $host
     * @param int    $port
     */
    /*public function __construct($host = '0.0.0.0', $port = 8090)
    {
        $this->host = $host;
        $this->port = $port;
    }*/


    /**
     * Resolve application.
     *
     * @return void
     */
    protected function resolveApplication()
    {
        if (!$this->appSnapshot) {
            $this->appSnapshot = require $this->basePath('bootstrap/app.php');
        }
    }

    /**
     * Get the base path for the application.
     *
     * @param string|null $path
     *
     * @return string
     */
    public function basePath($path = null)
    {
        if (defined("ROOT_PATH")) {
            return ROOT_PATH.($path ? '/'.$path : $path);
        }
        return substr(__DIR__,0, -29 ).($path ? '/'.$path : $path);
    }

    /**
     * Determine if server is running.
     *
     * @return bool
     */
    public function isRunning()
    {
        if (!file_exists($this->pidFile)) {
            return false;
        }
        $pid = file_get_contents($this->pidFile);
        return (bool) posix_getpgid($pid);
    }
    /**
     * Set http server options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        $this->options = array_only($options, static::$validServerOptions);
        return $this;
    }

    /**
     * Server shutdown event callback.
     */
    public function onShutdown()
    {
        unlink($this->pidFile);
    }

    protected $processor = null;
    protected $serviceName = 'RpcService';

    abstract public function onReceive($serv, $fd, $from_id, $data);
    /*{
        $transport = $this->transport_->accept();
        $transport->setServer($serv);
        $transport->setNetFD($fd);
        $transport->setData($data);
        $inputTransport = $this->inputTransportFactory_->getTransport($transport);
        $outputTransport = $this->outputTransportFactory_->getTransport($transport);
        $inputProtocol = $this->inputProtocolFactory_->getProtocol($inputTransport);
        $outputProtocol = $this->outputProtocolFactory_->getProtocol($outputTransport);
        register_shutdown_function([$this, 'handleLumenShutdown'], $this->processor_, $outputProtocol);
        try {
            $this->processor_->process($inputProtocol, $outputProtocol);
        } catch (\Exception $e) {
            $log = "remote call error: " . $e->getCode() . '--msg:' . $e->getMessage() . PHP_EOL. $e->getTraceAsString();
            echo $log . PHP_EOL;
            getGouuseCore()->LogLib->error($e->getTraceAsString());
        }
        getGouuseCore()->distroy();
        OptionHelper::distroyGouuse();//注销容器
    }*/

    abstract public function handleLumenShutdown($processor_, $outputProtocol);
    /*{
        if ($error = error_get_last()) {
            getGouuseCore()->LogLib->error($error['message'], $error);
        } else {
            getGouuseCore()->LogLib->error("rpc swoole error");
        }
    }*/

    function onWorkerStart()
    {
        echo "ThriftServer Start\n";
        if (!defined('SERVER_TYPE')) {
            define('SERVER_TYPE', 1);//定义服务方式为Swoole
        }
        $this->resolveApplication();
    }

    function notice($log)
    {
        echo $log."\n";
    }

    public function setServer($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    function onConnect($server, $fd, $reactorId)
    {
        echo "new";
    }

    function serve()
    {
        $this->pidFile = $this->options['pid_file'];
        if ($this->isRunning()) {
            throw new \Exception('The server is already running.');
        }

        $this->server = new \Swoole\Server($this->host, $this->port);
        if (!empty($this->options)) {
            $this->server->set($this->options);
        }
        $this->server->on('workerStart', [$this, 'onWorkerStart']);
        $this->server->on('receive', [$this, 'onReceive']);
        $this->server->on('connect', [$this, 'onConnect']);
        $this->server->start();
    }

    /**
     * Stops the server serving
     *
     * @return void
     */
    public function stop()
    {
        // TODO: Implement stop() method.
        $this->server->shutdown();
    }
}