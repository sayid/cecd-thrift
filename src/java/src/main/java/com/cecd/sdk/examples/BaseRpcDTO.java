package com.cecd.sdk.examples;

import com.fasterxml.jackson.annotation.JsonProperty;
import lombok.Data;

@Data
public abstract class BaseRpcDTO<T> {
    @JsonProperty(value = "code", defaultValue = "0")
    private long code;
    @JsonProperty(value = "msg", defaultValue = "")
    private String msg;
    protected T data;
}
