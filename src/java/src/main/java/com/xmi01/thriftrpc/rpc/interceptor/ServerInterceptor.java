package com.xmi01.thriftrpc.rpc.interceptor;

import com.xmi01.thriftrpc.thrift.ResponseData;

import java.util.List;
import java.util.Map;

/**
 * 定义拦截器接口
 */
public abstract class ServerInterceptor {

    abstract public Map before(String classname, String method, Object[] arglist, Map<String, Object> extra);

    abstract public ResponseData after(ResponseData responseData);
}
