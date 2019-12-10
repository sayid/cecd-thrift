package com.cecd.sdk.examples.usercenter.entity;

import com.alibaba.fastjson.annotation.JSONField;
import com.cecd.sdk.rpc.RpcEntityIf;

public class Member implements RpcEntityIf {

    @JSONField(name = "member_id")
    private int memberId;

    @JSONField(name = "member_name")
    private String memberName;

    @JSONField(name = "mobile")
    private String mobile;

    public int getMemberId() {
        return memberId;
    }

    public void setMemberId(int memberId) {
        this.memberId = memberId;
    }

    public String getMemberName() {
        return memberName;
    }

    public void setMemberName(String memberName) {
        this.memberName = memberName;
    }

    public String getMobile() {
        return mobile;
    }

    public void setMobile(String mobile) {
        this.mobile = mobile;
    }
}
