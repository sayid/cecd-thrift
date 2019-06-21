
一、引入
 ```
<dependency>
    <groupId>cecd-sdk</groupId>
    <artifactId>rpc</artifactId>
    <version>版本号</version>
</dependency>
```


二、启动服务类

```aidl

public class Main {
    public static void main(String[] args) {
        RpcServiceImpl rpcService = new RpcServiceImpl();
        //注册拦截器 可以不用注册
        rpcService.setInterceptorIf(new RpcServerIntercepterTest());
        //启动服务后将会监听8090端口
        RpcServer rpc = new RpcServer(8090, rpcService);
        rpc.start();
    }
}
```

三、定义接口(具体编码请参照demo----examples包下)
```aidl
package com.cecd.sdk.examples.testcenter;

import com.cecd.sdk.rpc.RpcDoc;

//对应服务端具体的业务类
@RpcDoc("com.cecd.sdk.examples.servers.test")
public interface TestService {
     //定义方法
    public String test(String test);
}

```

四、编写服务端(具体编码请参照demo----examples包下)

```aidl
package com.cecd.sdk.examples.servers;

import java.util.Map;
import com.cecd.sdk.examples.testcenter.TestService;

public class Test implements TestService{

    //实现方法
    public String test(String test) {
        return test + "124";
    }
}

```

五、编写客户端sdk(具体编码请参照demo----examples包下)

```aidl

package com.cecd.sdk.examples.testcenter;

import com.cecd.sdk.examples.RpcClientIntercepterTest;
import com.cecd.sdk.rpc.RpcDoc;
import com.cecd.sdk.rpc.RpcFactory;
import com.cecd.sdk.rpc.RpcModuleAbstract;


public class TestCenter extends RpcModuleAbstract {

    public TestCenter() {
        
        //设置客户端拦截器   
        this.setInterceptor(new RpcClientIntercepterTest());
        
        //设置该模块id
        this.setServiceId(1005);
        //设置该模块名称
        this.setServiceName("user-center");
        //设置服务端预研类型
        this.setLang("java");
        
        //将客户端放入rpc工厂中统一维护
        RpcFactory.addService(this);
    }
    
}

```


六、发起调用
```aidl

import com.cecd.sdk.examples.testcenter.TestCenter;
import com.cecd.sdk.examples.testcenter.TestService;


TestCenter testCenter = new TestCenter();
testCenter.setHost("127.0.0.1").setPort(8090);
System.out.println(testCenter.getLang());
TestService test = testCenter.getRpc(TestService.class);
System.out.println(test.test("alibaba"));
```