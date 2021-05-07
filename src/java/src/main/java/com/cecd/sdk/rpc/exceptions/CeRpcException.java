package com.xmi01.thriftrpc.rpc.exceptions;

public class CeRpcException extends Exception {
    private String message;
    private String ex;
    private StackTraceElement[] stackTrace;
    private long code = 1000;
    public CeRpcException(String message, StackTraceElement[] stackTrace) {
        super(message);
        this.message = message;
        this.stackTrace = stackTrace;
        this.ex = stackTraceToString(stackTrace);
    }
    public CeRpcException(long code, String message, String ex) {
        super(message);
        this.message = message;
        this.ex = ex;
        this.code = code;
    }
    public CeRpcException(String message, String ex) {
        super(message);
        this.message = message;
        this.ex = ex;
    }
    public CeRpcException(String message) {
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

    private String stackTraceToString(StackTraceElement[] stackTraceElements)
    {
        StringBuilder sb = new StringBuilder();
        for(StackTraceElement stackTraceElemen : stackTraceElements)
        {
            sb.append(stackTraceElemen.toString()+"\n");
        }
        return sb.toString();
    }

}
