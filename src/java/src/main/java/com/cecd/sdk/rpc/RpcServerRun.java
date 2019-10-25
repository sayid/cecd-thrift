package com.cecd.sdk.rpc;

import com.cecd.sdk.thrift.RpcService;
import org.apache.thrift.TProcessor;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.server.TNonblockingServer;
import org.apache.thrift.server.TServer;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TNonblockingServerSocket;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class RpcServerRun {

    private static final Logger LOGGER = LoggerFactory.getLogger(RpcServerRun.class);

    /**
     *
     * @param port
     * @param rpcServiceImpl 需要通过bean获取到的类
     */
    public static void start(int port, RpcServiceImpl rpcServiceImpl) {
        try {
            TProcessor processor = new RpcService.Processor(rpcServiceImpl);
            LOGGER.info("Rpc Server start... port:" + port);
            TNonblockingServerSocket tnbSocketTransport = new TNonblockingServerSocket(port);
            TNonblockingServer.Args tnbArgs = new TNonblockingServer.Args(
                    tnbSocketTransport);
            tnbArgs.processor(processor);
            tnbArgs.transportFactory(new TFramedTransport.Factory());
            tnbArgs.protocolFactory(new TBinaryProtocol.Factory());

            // 使用非阻塞式IO，服务端和客户端需要指定TFramedTransport数据传输的方式
            TServer server = new TNonblockingServer(tnbArgs);
            server.serve();

        } catch (Exception e) {
            LOGGER.info("Rpc Server start error");
            e.printStackTrace();
        }
    }
}
