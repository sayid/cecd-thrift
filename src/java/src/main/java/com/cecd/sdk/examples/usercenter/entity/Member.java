package com.cecd.sdk.examples.usercenter.entity;

import com.cecd.sdk.rpc.RpcEntityIf;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

@JsonIgnoreProperties(ignoreUnknown = true)
public class Member implements RpcEntityIf {

    @JsonProperty("member_id")
    private int memberId;

    @JsonProperty("member_name")
    private String memberName;

    @JsonProperty("mobile")
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
