package com.cecd.sdk.examples;

import com.cecd.sdk.rpc.RpcClient;

import java.util.HashMap;
import java.util.Map;

public class RpcClientTest extends RpcClient {

    public Map prepareExtra() {
        Map map = new HashMap();
        map.put("a", "test1");
        map.put("b", "test2");
        System.out.println("alibaba");
        return map;
    }
}
