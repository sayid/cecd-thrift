package com.cecd.sdk.rpc;

import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.thrift.ResponseData;
import com.cecd.sdk.thrift.RpcService;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;
import org.springframework.core.env.Environment;

import java.lang.reflect.*;
import java.util.HashMap;

/**
 * rpc工厂类
 */
public class RpcFactory {

    private static RpcClient rpcClient;

    private static Environment environment;
    /**
     * 注册rpc
     */
    private static HashMap<String, RpcModuleIf> services = new HashMap();

    private static HashMap<String, String> rpcMap = new HashMap();

    public static RpcModuleIf getServiceByRpc(String rpcName)
    {
        String service = rpcMap.get(rpcName.toLowerCase());
        return services.get(service.toLowerCase());
    }

    public static void addService(RpcModuleIf rpc) {
        services.put(rpc.getClass().getName().toLowerCase(), rpc);
    }

    /**
     * 映射rpc类对应的rpc模块
     * @param rpcName
     * @param rpcServer
     */
    public static void setRpcMap(String rpcName,String rpcServer) {
        rpcMap.put(rpcName.toLowerCase(), rpcServer.toLowerCase());
    }

    /**
     * 设置公共配置
     * @param newEnvironment
     */
    public static void setEnvironment(Environment newEnvironment) {
        environment = newEnvironment;
    }

    public static Environment getEnvironment() {
        return environment;
    }


    public static void setRpcClient(RpcClient newRpcClient) {
        rpcClient = newRpcClient;
    }

    public static RpcClient getRpcClient() {
       return rpcClient;
    }

    public static <T> T getInstance(Class<T> clazz) {
        if (RpcFactory.services.containsKey(clazz.getClass().getName().toLowerCase())) {
            return (T)RpcFactory.services.get(clazz.getClass().getName().toLowerCase());
        }
        Class clazz1 = null;
        try {
            clazz1 = Class.forName(clazz.getName());
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }
        Object obj = null;
        try {
            obj = clazz1.newInstance();
        } catch (InstantiationException e) {
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            e.printStackTrace();
        }
        RpcFactory.addService((RpcModuleIf)obj);
        return (T) obj;
    }
}
