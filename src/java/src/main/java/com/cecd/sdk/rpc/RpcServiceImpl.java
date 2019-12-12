package com.cecd.sdk.rpc;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONArray;
import com.alibaba.fastjson.JSONObject;
import com.alibaba.fastjson.serializer.SerializeConfig;
import com.cecd.sdk.rpc.interceptor.ServerInterceptor;
import com.cecd.sdk.thrift.InvalidException;
import com.cecd.sdk.thrift.ResponseData;
import com.cecd.sdk.thrift.RpcService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import java.lang.reflect.Array;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

@Component
public class RpcServiceImpl implements RpcService.Iface {

    private ServerInterceptor interceptor;

    @Autowired
    RpcBeanFactory rpcBeanFactory;

    public void setInterceptorIf(ServerInterceptor interceptorIf) {
        this.interceptor = interceptorIf;
    }

    public ResponseData callRpc(String classname, String method, String arglist, String extra) {

        Object[] argsObj =  JSONObject.parseArray(arglist).toArray();
        JSONObject extraObj = JSONObject.parseObject(extra);

        if (null != interceptor) {
            /**
             * 前置拦截，做一些逻辑处理
             */
            Object beforeData;
            beforeData = interceptor.before(classname, method, argsObj, extraObj);
            if (beforeData instanceof ResponseData) {
                return (ResponseData)beforeData;
            }
        }

        ResponseData responseData = new ResponseData();
        responseData.setCode(0);

        Class clazz = null;
        JSONArray parameterTypes = extraObj.getJSONArray("parameterTypes");
        Class<?>[] argsTypes = new Class[parameterTypes.size()];
        try {
            for (int index = 0; index < parameterTypes.size(); index++) {
                String type = (String) parameterTypes.get(index);
                if (type.equals("String")) {
                    argsTypes[index] = String.class;
                } else if (type.equals("Integer")) {
                    argsTypes[index] = Integer.class;
                } else if (type.equals("Float")) {
                    argsTypes[index] = Float.class;
                } else if (type.equals("Double")) {
                    argsTypes[index] = Double.class;
                } else if (type.equals("Boolean")) {
                    argsTypes[index] = Boolean.class;
                } else if (type.equals("Array")) {
                    argsTypes[index] = Array.class;
                } else if (type.equals("JSONObject")) {
                    //复杂对象只能使用json对象传递
                    argsTypes[index] = JSONObject.class;
                } else {
                    if (type.contains(".")) {
                        //如果类型是一个实体类，则需要转换成对应的
                        argsTypes[index] = Class.forName(type);
                        argsObj[index] = JSONObject.parseObject(JSONObject.toJSONString(argsObj[index]), argsTypes[index]);
                    } else {
                        //只允许基础类型
                        responseData.setCode(1000);
                        responseData.setMsg(classname + " method parameter type err");
                    }
                }
                System.out.println(argsTypes[index].getName());
            }
        } catch (Exception e) {
            e.printStackTrace();
            responseData.setCode(1000);
            responseData.setMsg(classname + " method parameter type err");
            responseData.setEx(e.getMessage());
        }

        if (responseData.getCode() == 0) {
            try {
                clazz = Class.forName(classname);
            } catch (ClassNotFoundException e) {
                e.printStackTrace();
                responseData.setCode(1000);
                responseData.setMsg(classname + " not found");
                responseData.setEx(e.getMessage());
            }
        }

        if (responseData.getCode() == 0) {
            RpcServiceInterface rpcServiceInterface = null;
            try {
                rpcServiceInterface = (RpcServiceInterface) rpcBeanFactory.getApplicationContext().getBean(clazz);
            } catch (Exception e) {
                e.printStackTrace();
                responseData.setCode(1000);
                responseData.setMsg(classname + " is not bean");
                responseData.setEx(e.getMessage());
            }
            if (responseData.getCode() == 0) {
                Method m = null;
                try {
                    m = clazz.getMethod(method, argsTypes);
                } catch (NoSuchMethodException e) {
                    e.printStackTrace();
                    responseData.setCode(1000);
                    responseData.setMsg(classname + " method " + method + " not exist");
                    responseData.setEx(e.getMessage());
                }
                if (responseData.getCode() == 0) {
                    Object data = null;
                    try {
                        data = m.invoke(rpcServiceInterface, argsObj);
                        System.out.println("result is " + data);
                        responseData.setCode(0);
                        responseData.setData(JSONObject.toJSONString(data));
                    } catch (Exception e) {
                        e.printStackTrace();
                        responseData.setCode(1000);
                        responseData.setEx(e.getMessage());
                        responseData.setStrace(JSONObject.toJSONString(e.getStackTrace()));
                    }
                }
            }
        }

        if (null != interceptor) {
            /**
             * 后置拦截，做一些逻辑处理
             */
            responseData = interceptor.after(responseData);
        }

        return responseData;
    }


}
