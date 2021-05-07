package com.xmi01.thriftrpc.rpc.interceptor;

import java.util.Map;

/**
 * 定义拦截器接口
 */
public abstract class ClientInterceptor {

    abstract public Map before();
}
