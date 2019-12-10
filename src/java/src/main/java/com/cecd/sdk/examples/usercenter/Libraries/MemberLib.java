package com.cecd.sdk.examples.usercenter.Libraries;

import com.cecd.sdk.examples.usercenter.entity.Member;
import com.cecd.sdk.rpc.RpcDoc;
import com.cecd.sdk.rpc.RpcInterface;

@RpcDoc("App\\Libraries\\MemberLib")
public interface MemberLib extends RpcInterface {
    public Member getSimpleMemberById(int member_id) ;
}
