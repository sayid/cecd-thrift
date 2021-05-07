package com.xmi01.thriftrpc.examples.usercenter.rpcs;

import com.xmi01.thriftrpc.examples.usercenter.entity.Member;
import com.xmi01.thriftrpc.rpc.RpcDoc;
import com.xmi01.thriftrpc.rpc.RpcInterface;
import com.xmi01.thriftrpc.rpc.exceptions.CeRpcException;

@RpcDoc("App\\Rpcs\\MemberLibRpc")
public interface MemberLibRpc extends RpcInterface {
    Member getSimpleMemberById(int member_id) throws CeRpcException;
}
