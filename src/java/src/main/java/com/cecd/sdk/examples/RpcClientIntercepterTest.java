package com.cecd.sdk.examples;

import com.cecd.sdk.rpc.interceptor.ClientInterceptor;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.util.HashMap;
import java.util.Map;

public class RpcClientIntercepterTest extends ClientInterceptor {

    private static final Logger LOGGER = LoggerFactory.getLogger(RpcClientIntercepterTest.class);
    public Map before() {
        Map map = new HashMap();
        map.put("a", "test1");
        map.put("b", "test2");
        LOGGER.info("alibaba");
        return map;
    }
}
