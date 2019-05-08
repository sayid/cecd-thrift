package com.cecd.sdk.rpc;

import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.thrift.InvalidException;
import com.cecd.sdk.thrift.ResponseData;
import com.cecd.sdk.thrift.RpcService;

public class RpcServiceImpl implements RpcService.Iface {

    public ResponseData callRpc(java.lang.String classname, java.lang.String method, java.lang.String arglist, java.lang.String extra) throws InvalidException, org.apache.thrift.TException {
        //List args = (List)MsgPackUtil.toObject(arglist.getBytes());
        //Map extr = JSONObject.parseObject(extra);
        return null;
    }

}
