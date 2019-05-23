package com.cecd.sdk.examples.usercenter.Libraries;

import com.cecd.sdk.examples.usercenter.Models.MemberModel;
import com.cecd.sdk.rpc.RpcDoc;
import com.cecd.sdk.rpc.RpcInterface;

@RpcDoc("App\\Libraries\\MemberLib")
public interface MemberLib extends RpcInterface {
    public MemberModel getSimpleMemberById(int member_id) ;
}
