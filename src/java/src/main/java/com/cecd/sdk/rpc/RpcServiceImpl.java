package com.cecd.sdk.rpc;

import com.alibaba.fastjson.JSONArray;
import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.thrift.InvalidException;
import com.cecd.sdk.thrift.ResponseData;
import com.cecd.sdk.thrift.RpcService;

import java.lang.reflect.Array;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

abstract class RpcServiceImpl implements RpcService.Iface {

    abstract protected Object before(String classname, String method, Object[] argsObj, JSONObject extraObj);

    public ResponseData callRpc(String classname, String method, String arglist, String extra) throws InvalidException, org.apache.thrift.TException {

        Object[] argsObj =  JSONObject.parseArray(arglist).toArray();
        JSONObject extraObj = JSONObject.parseObject(extra);

        /**
         * 前置拦截，做一些逻辑处理
         */
        Object beforeData = before(classname, method, argsObj, extraObj);
        if (beforeData instanceof ResponseData) {
            return (ResponseData)beforeData;
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
                    argsTypes[index] = com.alibaba.fastjson.JSONObject.class;
                } else {
                    //只允许基础类型
                    responseData.setCode(1000);
                    responseData.setEx(classname + " method parameter type err");
                }
                System.out.println(argsTypes[index].getName());
            }
        } catch (Exception e) {
            e.printStackTrace();
            responseData.setCode(1000);
            responseData.setEx(classname + " method parameter type err");
        }

        if (responseData.getCode() == 0) {
            try {
                clazz = Class.forName(classname);
            } catch (ClassNotFoundException e) {
                e.printStackTrace();
                responseData.setCode(1000);
                responseData.setEx(classname + " not found");
            }
        }

        if (responseData.getCode() == 0) {
            Object object = null;
            try {
                object = clazz.newInstance();
            } catch (InstantiationException e) {
                e.printStackTrace();
                responseData.setCode(1000);
                responseData.setEx(e.getMessage());
                responseData.setStrace(e.getStackTrace().toString());
            } catch (IllegalAccessException e) {
                e.printStackTrace();
                responseData.setCode(1000);
                responseData.setEx(e.getMessage());
                responseData.setStrace(e.getStackTrace().toString());
            }
            System.out.println(method);
            if (responseData.getCode() == 0) {
                Method m = null;
                try {
                    m = clazz.getMethod(method, argsTypes);
                } catch (NoSuchMethodException e) {
                    e.printStackTrace();
                    responseData.setCode(1000);
                    responseData.setEx(classname + " method " + method + " not exist");
                }
                if (responseData.getCode() == 0) {
                    Object data = null;
                    try {
                        data = m.invoke(object, argsObj);
                        System.out.println("result is " + data);
                        responseData.setCode(0);
                        responseData.setData(JSONObject.toJSONString(data));
                    } catch (IllegalAccessException e) {
                        e.printStackTrace();
                        responseData.setCode(1000);
                        responseData.setEx(e.getMessage());
                        responseData.setStrace(e.getStackTrace().toString());
                    } catch (InvocationTargetException e) {
                        e.printStackTrace();
                        responseData.setCode(1000);
                        responseData.setEx(e.getMessage());
                        responseData.setStrace(e.getStackTrace().toString());
                    }
                }
            }
        }
        return responseData;
    }


}
