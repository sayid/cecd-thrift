
import com.cecd.sdk.examples.RpcServerIntercepterTest;
import com.cecd.sdk.rpc.RpcServer;
import com.cecd.sdk.rpc.RpcServiceImpl;

public class Main {
    public static void main(String[] args) {
        RpcServiceImpl rpcService = new RpcServiceImpl();
        //注册拦截器
        rpcService.setInterceptorIf(new RpcServerIntercepterTest());
        RpcServer rpc = new RpcServer();
        rpc.run();
    }
}
