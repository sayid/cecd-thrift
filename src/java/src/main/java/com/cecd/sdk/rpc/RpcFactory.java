package com.cecd.sdk.rpc;

import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.rpc.authcenter.AuthCenter;
import com.cecd.sdk.rpc.usercenter.UserCenter;
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

    private static Environment environment;
    /**
     * 注册rpc
     */
    private static HashMap<String, RpcModuleIf> services = new HashMap();

    public static RpcModuleIf getService(String servicename)
    {
        return services.get(servicename);
    }

    public static void addService(RpcModuleIf rpc) {
        services.put(rpc.getClass().getSimpleName().toLowerCase(), rpc);
    }

    private static InvocationHandler handler = new InvocationHandler() {

        public Object invoke(Object proxy, Method method, Object[] args) throws Throwable, Exception {

            RpcNetModel rpcModel = new RpcNetModel();
            Class<?>[] classes = proxy.getClass().getInterfaces();
            String className = classes[0].getName();
            String packagename = classes[0].getPackage().getName();
            String current_package = this.getClass().getPackage().getName();

            int pos = current_package.length() + 1;
            int pos_end = packagename.indexOf(".", pos);
            String module = packagename.substring(pos, pos_end);
            RpcModuleIf rpcLoader = services.get(module);
            if (null == rpcLoader.getHost() || rpcLoader.getHost().length() == 0) {
                throw new Exception(rpcLoader.getClass().getName()+"未配置host");
            }
            RpcDoc rpcDoc = classes[0].getAnnotation(RpcDoc.class);
            if (rpcDoc == null) {
                throw new NullPointerException("未定义rpc"+className);
            }
            String classpath = rpcDoc.value();
            System.out.println(classpath);
            TTransport transport = null;
            ResponseData result = null;
            if (rpcLoader.getTimeout() > 0) {
                System.out.println("rpc->host:" + rpcLoader.getHost() + " port:" + rpcLoader.getPort() + " timeout:" + rpcLoader.getTimeout());
                transport = new TFramedTransport(new TSocket(rpcLoader.getHost(), rpcLoader.getPort(), rpcLoader.getTimeout()));
            } else {
                System.out.println("rpc->host:" + rpcLoader.getHost() + " port:" + rpcLoader.getPort());
                transport = new TFramedTransport(new TSocket(rpcLoader.getHost(), rpcLoader.getPort()));
            }
            // 协议要和服务端一致
            TProtocol protocol = new TBinaryProtocol(transport);

            RpcService.Client client = new RpcService.Client(protocol);
            transport.open();

            result = client.callRpc(classpath, method.getName(), JSONObject.toJSONString(args), "");
            transport.close();
            System.out.println("Thrift client result=" + result);

            Type type = method.getReturnType();
            System.out.println(method.getReturnType());
            return JSONObject.parseObject(result.getData(), type);
        }
    };

    @SuppressWarnings("unchecked")
    public static <T> T getInstance(Class<T> clazz) {
        return (T) Proxy.newProxyInstance(clazz.getClassLoader(),
                new Class[]{clazz}, handler );
    }
}
