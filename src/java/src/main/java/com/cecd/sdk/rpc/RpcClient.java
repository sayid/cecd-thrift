package com.cecd.sdk.rpc;

import java.util.Map;

/**
 * 如果需要自定义一些传输的数据，需要实现此类
 */
abstract public class RpcClient {

    abstract protected Map prepareExtra();
}
