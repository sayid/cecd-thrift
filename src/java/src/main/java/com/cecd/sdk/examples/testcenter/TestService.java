package com.xmi01.thriftrpc.examples.testcenter;

import com.xmi01.thriftrpc.rpc.RpcDoc;

@RpcDoc("com.cecd.sdk.examples.servers.test")
public interface TestService {
    public String test(String test);
}
