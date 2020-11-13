package com.cecd.sdk.examples.usercenter.entity;

import com.cecd.sdk.rpc.RpcEntityIf;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;
import lombok.Data;

import java.io.Serializable;

@Data
@JsonIgnoreProperties(ignoreUnknown = true)
public class Company implements RpcEntityIf, Serializable {

    @JsonProperty("company_id")
    private int companyId;

    @JsonProperty("company_name")
    private String companyName;

    @JsonProperty("logo")
    private String logo;

    @JsonProperty("third_system")
    private int thirdSystem;

    @JsonProperty("admin_id")
    private int adminId;

    @JsonProperty("website")
    private String website;

    @JsonProperty("register_domain")
    private String registerDomain;

    @JsonProperty("ce_corp_account")
    private String ceCorpAccount;

    //企业来源 中企 新网 其他
    @JsonProperty(value = "channel_source", defaultValue = "1")
    private String channelSource;

}
