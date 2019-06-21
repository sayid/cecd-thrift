package com.cecd.sdk.examples.testcenter;

import com.cecd.sdk.rpc.RpcDoc;

@RpcDoc("com.cecd.sdk.examples.servers.test")
public interface TestService {
    public String test(String test);
}
