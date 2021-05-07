package com.cecd.sdk.rpc;


import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.core.env.Environment;

import java.util.HashMap;

/**
 * rpc工厂类
 */
public class RpcFactory {

    private static Environment environment;
    /**
     * 注册rpc
     */
    private static HashMap<String, RpcModuleIf> services = new HashMap();

    private static HashMap<String, String> rpcMap = new HashMap();

    private static final Logger LOGGER = LoggerFactory.getLogger(RpcFactory.class);

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
        LOGGER.info("environment config init");
    }

    public static Environment getEnvironment() {
        return environment;
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
