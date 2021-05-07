package com.cecd.sdk.rpc;
import com.xmi01.thriftrpc.thrift.RpcService;
import org.apache.thrift.TProcessor;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.server.THsHaServer;
import org.apache.thrift.server.TNonblockingServer;
import org.apache.thrift.server.TServer;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TNonblockingServerSocket;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Scope;
import org.springframework.core.annotation.Order;
import org.springframework.stereotype.Component;

import javax.annotation.PostConstruct;

@Component
public class RpcServerRun {

    @Autowired
    RpcServiceImpl rpcServiceImpl;

    //监听的端口
    @Value("${server.thrift.enabled:false}")
    private Boolean enabled = false;

    //监听的端口
    @Value("${server.thrift.port:8090}")
    private Integer port;

    //线程池最小线程数
    @Value("${server.thrift.min-thread-pool:1}")
    private Integer minThreadPool;

    //线程池最大线程数
    @Value("${server.thrift.max-thread-pool:10}")
    private Integer maxThreadPool;

    private static final Logger LOGGER = LoggerFactory.getLogger(RpcServerRun.class);

    public void run() {
        if (!enabled) {
            LOGGER.info("Rpc Server enabled:" + enabled);
            return;
        }
        try {
            TProcessor processor = new RpcService.Processor(rpcServiceImpl);
            LOGGER.info("Rpc Server start... port:" + port + ", minThreadPool:"+minThreadPool+", maxThreadPool:"+maxThreadPool);
            TNonblockingServerSocket tnbSocketTransportSocket = new TNonblockingServerSocket(port);
            THsHaServer.Args tnbArgs = new THsHaServer.Args(
                    tnbSocketTransportSocket).minWorkerThreads(minThreadPool).maxWorkerThreads(maxThreadPool);
            tnbArgs.processor(processor);
            tnbArgs.transportFactory(new TFramedTransport.Factory());
            tnbArgs.protocolFactory(new TBinaryProtocol.Factory());

            // 使用非阻塞式IO，服务端和客户端需要指定TFramedTransport数据传输的方式
            TServer server = new THsHaServer(tnbArgs);
            server.serve();

        } catch (Exception e) {
            LOGGER.info("Rpc Server start error");
            e.printStackTrace();
        }
    }
}
