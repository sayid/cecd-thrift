package com.cecd.sdk.rpc.usercenter;


import com.cecd.sdk.rpc.RpcModuleIf;

public class UserCenter implements RpcModuleIf {

    private int serviceId = 1006;

    private String serviceName = "user-center";

    protected String host = "";

    protected int port = 8090;

    private int timeout = 1000;

    private String lang = "php";

    private static UserCenter rpcLoader;

    public static UserCenter getInstance() {
        if (null == rpcLoader) {
            rpcLoader = new UserCenter();
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
