package com.cecd.sdk.examples;


import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.rpc.interceptor.ServerInterceptor;
import com.cecd.sdk.thrift.ResponseData;

import java.util.HashMap;
import java.util.Map;

public class RpcServerIntercepterTest extends ServerInterceptor {

    public Map before(String classname, String method, Object[] arglist, JSONObject extra) {
        Map map = new HashMap();
        map.put("a", "test1");
        map.put("b", "test2");
        System.out.println("alibaba");
        return map;
    }

    public ResponseData after(ResponseData responseData) {
        return responseData;
    }
}
