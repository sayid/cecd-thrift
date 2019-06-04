基于thrift的抽象封装而来，使用本项目包后环境中无需按安装thrift环境可直接使用

使用：php7.1+(仅支持lumen5.5+)，Swoole4.2+，spring boot，thrift0.12

一、php端
    首先引入composer:     "cecd/thrift": "dev-master"

1.1 启动服务代码   启动服务后会监听8090方法
 
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
    
1.2 服务端rpc被调用的方法
    
    class AccountLib
    {
       
        /**
         * @rpc 对外提供token是否登录有效的方法
         * @param string $token
         * @param number $client_type 客户端类型  0 web  1 app
         * @return number[]|number[]|number[][]
         */
        public function check($token = '')
        {
            return "ok";
        }
     }
    

1.3 编写rpc模块目录
    --AuthCenter(可以是任意目录)
        --AuthCenter.php(可以是任意名称)
        --Libraries(可以是任意目录)
            --AccountLib.php(接口)
    
    
    
1.4 编写客户端模块主类

    namespace Cecd\Sdk\Rpc\AuthCenter;
    
    use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;
    use \Thrift\CeCd\Sdk\Core\Command\RpcModuleTrait;
    
    /**
     * Class AuthCenter
     * @package Cecd\Sdk\Rpc\AuthCenter
     * @property \Cecd\Sdk\Rpc\AuthCenter\Libraries\AccountLib $AccountLib    
     */
    class AuthCenter implements RpcModuleIf
    {
        use RpcModuleTrait;
    
        private $serviceId = 1006;
    
        private $config_key = 'rpc.auth-center';
    
        private $lang = "php";
    
        private $rpcs = [
            \Cecd\Sdk\Rpc\AuthCenter\Libraries\AccountLib::class   这里需要定义客户端锁包含的类
        ];
    
        public function __construct()
        {

        }
    }
    
1.5  编写客户端接口

    1、编写rpc接口
    
    namespace Cecd\Sdk\Rpc\AuthCenter\Libraries;
    
    use Thrift\CeCd\Sdk\Core\Services\RpcInterface;
    
    /**
     * Interface AccountLib
     * @package Cecd\Sdk\Rpc\AuthCenter\Libraries
     * @classpath(\App\Libraries\AccountLib)
     */
    interface AccountLib extends RpcInterface
    {
        public function check(string  $token) : array ;
    }
    
1.6 使用

    $authCenter = new AuthCenter();
    //设置服务器所在的地址和端口
    $authCenter->setPort(8090)->setHost("127.0.0.1");
    //这就会输出服务端AccountLib->check()方法返回的数据ok
    echo $authCenter->AccountLib->check("test");

    
    
    
作者qq:449063862
