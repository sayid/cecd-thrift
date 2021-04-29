package com.cecd.sdk.examples.usercenter.rpcs;

import com.cecd.sdk.examples.usercenter.dto.CompanyInfoDTO;
import com.cecd.sdk.examples.usercenter.entity.Member;
import com.cecd.sdk.rpc.RpcDoc;
import com.cecd.sdk.rpc.RpcInterface;
import com.cecd.sdk.rpc.exceptions.CeRpcException;

@RpcDoc("App\\Rpcs\\CompanyLibRpc")
public interface CompanyLibRpc extends RpcInterface {
    CompanyInfoDTO getByCompanyId(int companyId);
}
