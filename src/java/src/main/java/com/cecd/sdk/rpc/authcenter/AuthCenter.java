package com.cecd.sdk.rpc.authcenter;


import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.rpc.*;
import com.cecd.sdk.thrift.ResponseData;
import com.cecd.sdk.thrift.RpcService;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.core.env.Environment;

import java.lang.reflect.InvocationHandler;
import java.lang.reflect.Method;
import java.lang.reflect.Proxy;
import java.lang.reflect.Type;

public class AuthCenter extends RpcModuleAbstract implements RpcModuleIf {

    private int serviceId = 1005;

    private String serviceName = "auth-center";

    protected String host;

    protected int port = 0;

    private int timeout = 10000;

    private String lang = "php";

    private static AuthCenter rpcLoader;

    public AuthCenter(){
        //初始化后放入工厂类中
        RpcFactory.addService(this);
    };
    public AuthCenter(String newHost, int newPort) {
        this.host = newHost;
        this.port = newPort;
        //初始化后放入工厂类中
        RpcFactory.addService(this);
    }

    public static AuthCenter getInstance(Environment environment) {
        if (null == rpcLoader) {
            rpcLoader = new AuthCenter();
            String newHost = environment.getProperty("rpc."+rpcLoader.getServiceName()+".host");
            if (null == newHost) {
                throw new NullPointerException("rpc.auth-center.host is undefined");
            }
            String newPort = environment.getProperty("rpc."+rpcLoader.getServiceName()+".port");
            if (null == newHost) {
                throw new NullPointerException("rpc.auth-center.port is undefined");
            }
            rpcLoader.setHost(newHost);
            rpcLoader.setPort(Integer.valueOf(newPort));
            rpcLoader = new AuthCenter(newHost, Integer.valueOf(newPort));
        }
        return rpcLoader;
    }

    public int getServiceId() {
        return serviceId;
    }

    public String getServiceName() {
        return serviceName;
    }

    public String getHost() {
        return host;
    }

    public AuthCenter setHost(String host) {
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

    public RpcModuleIf setTimeout(int timeout) {
        this.timeout = timeout;
        return this;
    }
}
