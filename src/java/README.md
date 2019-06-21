
一、引入
 ```
<dependency>
    <groupId>cecd-sdk</groupId>
    <artifactId>rpc</artifactId>
    <version>版本号</version>
</dependency>
```


二、启动服务

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

三、定义接口


四、编写服务端



五、编写客户端(具体编码请参照demo----examples包下)




六、发起调用
```aidl
TestCenter testCenter = new TestCenter();
testCenter.setHost("127.0.0.1").setPort(8090);
System.out.println(testCenter.getLang());
TestService test = testCenter.getRpc(TestService.class);
System.out.println(test.test("alibaba"));
```