package com.cecd.sdk.rpc;

public interface RpcModuleIf {

    public int getServiceId();

    public String getServiceName();

    public String getHost();

    public RpcModuleIf setHost(String host);

    public int getPort();

    public RpcModuleIf setPort(int port);

    public String getLang();

    public int getTimeout();

    public RpcModuleIf setTimeout(int timeout);

    public void setInterceptor(Class interceptor);

    public Class getInterceptor();
}
