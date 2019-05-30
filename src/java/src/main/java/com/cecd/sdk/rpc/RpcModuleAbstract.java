package com.cecd.sdk.rpc;

import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.thrift.ResponseData;
import com.cecd.sdk.thrift.RpcService;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;

import java.lang.reflect.InvocationHandler;
import java.lang.reflect.Method;
import java.lang.reflect.Proxy;
import java.lang.reflect.Type;
import java.util.HashMap;
import java.util.Map;

public abstract class RpcModuleAbstract  {

    private static InvocationHandler handler = new InvocationHandler() {

        public Object invoke(Object proxy, Method method, Object[] args) throws Throwable, Exception {

            Map extraData = new HashMap();
            if (null != RpcFactory.getRpcClient()) {
                extraData = RpcFactory.getRpcClient().prepareExtra();
            }
            Class<?>[] classes = proxy.getClass().getInterfaces();
            String className = classes[0].getName();

            RpcModuleIf rpcLoader = RpcFactory.getServiceByRpc(className);
            System.out.println(rpcLoader.getHost());
            if (null == rpcLoader.getHost() || rpcLoader.getHost().length() == 0) {
                //如果没有设置host和port则读取公共配置中的
                if (null == RpcFactory.getEnvironment()) {
                    throw new NullPointerException("Environment is null");
                }
                String newHost = RpcFactory.getEnvironment().getProperty("rpc."+rpcLoader.getServiceName()+".host");
                if (null == newHost) {
                    throw new NullPointerException("rpc."+rpcLoader.getServiceName()+".host is undefined");
                }
                String newPort = RpcFactory.getEnvironment().getProperty("rpc."+rpcLoader.getServiceName()+".port");
                if (null == newHost) {
                    throw new NullPointerException("rpc."+rpcLoader.getServiceName()+".port is undefined");
                }
                rpcLoader.setHost(newHost);
                rpcLoader.setPort(Integer.valueOf(newPort));
            }
            RpcDoc rpcDoc = classes[0].getAnnotation(RpcDoc.class);
            if (rpcDoc == null) {
                throw new NullPointerException("未定义rpc"+className);
            }
            String classpath = rpcDoc.value();
            System.out.println("call rpc:" + classpath);
            TTransport transport = null;
            ResponseData result = null;
            if (rpcLoader.getTimeout() > 0) {
                System.out.println("rpc->host:" + rpcLoader.getHost() + " port:" + rpcLoader.getPort() + " timeout:" + rpcLoader.getTimeout());
                transport = new TFramedTransport(new TSocket(rpcLoader.getHost(), rpcLoader.getPort(), rpcLoader.getTimeout()));
            } else {
                System.out.println("rpc->host:" + rpcLoader.getHost() + " port:" + rpcLoader.getPort());
                transport = new TFramedTransport(new TSocket(rpcLoader.getHost(), rpcLoader.getPort()));
            }
            // 协议要和服务端一致
            TProtocol protocol = new TBinaryProtocol(transport);

            RpcService.Client client = new RpcService.Client(protocol);
            transport.open();

            result = client.callRpc(classpath, method.getName(), JSONObject.toJSONString(args), JSONObject.toJSONString(extraData));
            transport.close();
            System.out.println("Thrift client result=" + result);

            Type type = method.getReturnType();

            return JSONObject.parseObject(result.getData(), type);
        }
    };

    @SuppressWarnings("unchecked")
    public <T> T getRpc(Class<T> clazz) {
        //映射rpc接口对应的rpc模块名称
        RpcFactory.setRpcMap(clazz.getName(), this.getClass().getName());
        Object object = (T) Proxy.newProxyInstance(clazz.getClassLoader(),
                new Class[]{clazz}, handler );
        return (T)object;
    }
}
