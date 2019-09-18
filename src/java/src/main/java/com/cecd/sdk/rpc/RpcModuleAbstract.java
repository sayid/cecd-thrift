package com.cecd.sdk.rpc;

import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.rpc.interceptor.ClientInterceptor;
import com.cecd.sdk.thrift.ResponseData;
import com.cecd.sdk.thrift.RpcService;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;

import java.lang.reflect.*;
import java.util.HashMap;
import java.util.Map;

public abstract class RpcModuleAbstract implements RpcModuleIf  {

    private boolean debug = false;

    private int serviceId;

    private String serviceName;

    protected String host;

    protected int port;

    private int timeout = 1000;

    private String lang = "php";

    //客户端拦截器
    private ClientInterceptor interceptor;

    public int getServiceId() {
        return serviceId;
    }

    public RpcModuleIf setServiceId(int serviceId) {
        this.serviceId = serviceId;
        return this;
    }

    public String getServiceName() {
        return serviceName;
    }

    public String getHost() {
        return host;
    }

    public RpcModuleIf setHost(String host) {
        this.host = host;
        return this;
    }

    public int getPort() {
        return port;
    }

    public RpcModuleIf setPort(int port) {
        this.port = port;
        return this;
    }

    public String getLang() {
        return lang;
    }

    public int getTimeout() {
        return timeout;
    }

    public void setServiceName(String serviceName) {
        this.serviceName = serviceName;
    }

    public void setLang(String lang) {
        this.lang = lang;
    }

    public ClientInterceptor getInterceptor() {
        return interceptor;
    }

    public void setInterceptor(ClientInterceptor interceptor) {
        this.interceptor = interceptor;
    }

    public RpcModuleIf setTimeout(int timeout) {
        this.timeout = timeout;
        return this;
    }


    private static InvocationHandler handler = new InvocationHandler() {

        public Object invoke(Object proxy, Method method, Object[] args) throws Throwable, Exception {

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

            //处理传输一些额外数据
            Map extraData = new HashMap();
            if (null != rpcLoader.getInterceptor()) {
                extraData = rpcLoader.getInterceptor().before();
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

    public void setDebug(boolean debug) {
        this.debug = debug;
    }

    public boolean getDebug() {
        return debug;
    }
}
