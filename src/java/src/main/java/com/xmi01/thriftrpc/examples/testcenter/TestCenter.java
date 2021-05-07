package com.xmi01.thriftrpc.examples.testcenter;

import com.xmi01.thriftrpc.examples.RpcClientIntercepterTest;
import com.xmi01.thriftrpc.rpc.RpcFactory;
import com.xmi01.thriftrpc.rpc.RpcModuleAbstract;


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
