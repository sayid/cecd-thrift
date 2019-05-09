启动服务
    
    Cecd\Sdk\Rpc\Command::main($argv);
    
客户端：

    $authcenter = new \Cecd\Sdk\Rpc\AuthCenter\AuthCenter();
     $authcenter->setHost("127.0.0.1");
     $authcenter->setPort(8090);
     echo $authcenter->ping();
     print_r($authcenter->AccountLib->check());
     
          