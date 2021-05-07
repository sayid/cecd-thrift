package com.xmi01.thriftrpc.examples.usercenter.Libraries;

import com.xmi01.thriftrpc.examples.usercenter.entity.Member;
import com.xmi01.thriftrpc.rpc.RpcDoc;
import com.xmi01.thriftrpc.rpc.RpcInterface;
import com.xmi01.thriftrpc.rpc.exceptions.CeRpcException;

@RpcDoc("App\\Libraries\\MemberLib")
public interface MemberLib extends RpcInterface {
    public Member getSimpleMemberById(int member_id) throws CeRpcException;
}
