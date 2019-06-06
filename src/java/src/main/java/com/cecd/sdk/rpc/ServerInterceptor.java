package com.cecd.sdk.rpc;

import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.thrift.ResponseData;

import java.util.Map;

/**
 * 定义拦截器接口
 */
public abstract class ServerInterceptor {

    abstract public Map before(String classname, String method, Object[] arglist, JSONObject extra);

    abstract public ResponseData after(ResponseData responseData);
}
