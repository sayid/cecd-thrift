package com.cecd.sdk.examples.usercenter;

import com.cecd.sdk.examples.RpcClientIntercepterTest;
import com.cecd.sdk.rpc.RpcFactory;
import com.cecd.sdk.rpc.RpcModuleAbstract;
import com.cecd.sdk.rpc.RpcModuleIf;

public class UserCenter extends RpcModuleAbstract {


    public UserCenter() {
        //初始化后放入工厂类中
        this.setInterceptor(RpcClientIntercepterTest.class);
        this.setServiceId(1005);
        this.setServiceName("user-center");
        this.setLang("php");
        RpcFactory.addService(this);
    }


}
