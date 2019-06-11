package com.cecd.sdk.examples.testcenter;

import com.cecd.sdk.examples.RpcClientIntercepterTest;
import com.cecd.sdk.rpc.RpcDoc;
import com.cecd.sdk.rpc.RpcFactory;
import com.cecd.sdk.rpc.RpcModuleAbstract;

@RpcDoc("com.cecd.sdk.examples.servers.TestService")
public class TestCenter extends RpcModuleAbstract {

    public TestCenter() {
        //初始化后放入工厂类中
        this.setInterceptor(new RpcClientIntercepterTest());
        this.setServiceId(1005);
        this.setServiceName("user-center");
        this.setLang("java");
        RpcFactory.addService(this);
    }


}
