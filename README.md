基于thrift的抽象封装而来，使用本项目包后环境中无需按安装thrift环境可直接使用

使用：php7.1+(仅支持lumen5.5+)，Swoole4.2+，spring boot，thrift0.12

一、php端

--启动服务
 
    use Thrift\CeCd\Sdk\Core\Command\RpcServiceHandle;
    use Thrift\CeCd\Sdk\Core\TSwooleServerTransport;
    use Thrift\CeCd\Sdk\RpcServiceProcessor;
    use Thrift\Factory\TTransportFactory;
    use Thrift\Factory\TBinaryProtocolFactory;

    $this->host = "0.0.0.0";
    $this->port = 8090;
    $this->serverOptions = [];
    $this->serverOptions['worker_num'] = 4;
    $this->serverOptions['log_file'] = ROOT_PATH .'storage/logs/swoole.log';
    $handler = new RpcServiceHandle();

    $socket_tranport = new TSwooleServerTransport($this->host, $this->port);
    $out_factory = $in_factory = new TTransportFactory();
    $out_protocol = $in_protocol = new TBinaryProtocolFactory();
    $server = new \Thrift\CeCd\Sdk\Core\Command\SwooleServer($processor, $socket_tranport, $in_factory, $out_factory, $in_protocol, $out_protocol);

    $server->serve();
    
    


作者qq:449063862
