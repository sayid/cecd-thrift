package com.cecd.sdk.rpc;

import com.cecd.sdk.thrift.RpcService;
import org.apache.thrift.TProcessor;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.server.TNonblockingServer;
import org.apache.thrift.server.TServer;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TNonblockingServerSocket;

/**
 * rpc服务类
 */
public class RpcServer {

    private int port;

    RpcService.Iface rpcService;

    public RpcServer(int port, RpcService.Iface rpcService) {
        this.port = port;
        this.rpcService = rpcService;
    }

    public void start() {

        try {
            TProcessor processor = new RpcService.Processor(rpcService);
            System.out.println("Rpc Server start...");
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
            System.out.println("Rpc Server start error");
            e.printStackTrace();
        }
    }
}
