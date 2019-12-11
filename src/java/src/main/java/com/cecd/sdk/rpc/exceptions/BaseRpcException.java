package com.cecd.sdk.rpc.exceptions;

import org.springframework.web.util.pattern.PathPattern;

public class BaseRpcException extends Exception {
    private String message;
    private long code;
    private String ex;
    public BaseRpcException(long code, String message, String ex) {
        super(message);
        this.code = code;
        this.message = message;
        this.ex = ex;
    }
    public BaseRpcException(long code, String message) {
        super(message);
        this.code = code;
        this.message = message;
    }
}
