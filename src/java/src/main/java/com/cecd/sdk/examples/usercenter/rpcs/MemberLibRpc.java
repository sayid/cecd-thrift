package com.cecd.sdk.examples.usercenter.rpcs;

import com.cecd.sdk.examples.usercenter.entity.Member;
import com.cecd.sdk.rpc.RpcDoc;
import com.cecd.sdk.rpc.RpcInterface;
import com.cecd.sdk.rpc.exceptions.CeRpcException;

@RpcDoc("App\\Rpcs\\MemberLibRpc")
public interface MemberLibRpc extends RpcInterface {
    Member getSimpleMemberById(int member_id) throws CeRpcException;
}
