package com.cecd.sdk.rpc.authcenter.Libraries;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.rpc.RpcDoc;
import com.cecd.sdk.rpc.RpcInterface;

/**
 * 这里编写暴露的rpc客户端
 *
 */
@RpcDoc("App\\Libraries\\AccountLib")
public interface AccountLib extends RpcInterface {
    public JSONObject check(String accessToken);
}
