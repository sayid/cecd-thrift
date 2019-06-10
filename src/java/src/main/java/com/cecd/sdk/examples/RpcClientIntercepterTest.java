package com.cecd.sdk.examples;

import com.cecd.sdk.rpc.interceptor.ClientInterceptor;

import java.util.HashMap;
import java.util.Map;

public class RpcClientIntercepterTest extends ClientInterceptor {

    public Map before() {
        Map map = new HashMap();
        map.put("a", "test1");
        map.put("b", "test2");
        System.out.println("alibaba");
        return map;
    }
}
