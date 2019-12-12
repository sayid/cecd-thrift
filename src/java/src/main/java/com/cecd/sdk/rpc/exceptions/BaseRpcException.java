package com.cecd.sdk.rpc.exceptions;

public class BaseRpcException extends Exception {
    private String message;
    private String ex;
    private StackTraceElement[] stackTrace;
    private long code = 1000;
    public BaseRpcException(String message, StackTraceElement[] stackTrace) {
        super(message);
        this.message = message;
        this.stackTrace = stackTrace;
        this.ex = stackTrace.toString();
    }
    public BaseRpcException(long code,String message, String ex) {
        super(message);
        this.message = message;
        this.ex = ex;
        this.code = code;
    }
    public BaseRpcException(String message, String ex) {
        super(message);
        this.message = message;
        this.ex = ex;
    }
    public BaseRpcException(String message) {
        super(message);
        this.message = message;
    }

    @Override
    public String getMessage() {
        return message;
    }

    public String getEx() {
        return ex;
    }

    @Override
    public StackTraceElement[] getStackTrace() {
        return stackTrace;
    }

    public long getCode() {
        return code;
    }
}
