package com.cecd.sdk.examples.usercenter;

import com.cecd.sdk.examples.RpcClientTest;
import com.cecd.sdk.rpc.RpcFactory;
import com.cecd.sdk.rpc.RpcModuleAbstract;
import com.cecd.sdk.rpc.RpcModuleIf;

public class UserCenter extends RpcModuleAbstract implements RpcModuleIf {

    private int serviceId = 1006;

    private String serviceName = "user-center";

    protected String host;

    protected int port;

    private int timeout = 1000;

    private String lang = "php";

    public UserCenter() {
        //初始化后放入工厂类中
        System.out.println("ssss");
        RpcFactory.addService(this);
        RpcFactory.setRpcClient(new RpcClientTest());
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

    public UserCenter setHost(String host) {
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
