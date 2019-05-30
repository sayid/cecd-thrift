import com.cecd.sdk.examples.RpcServiceImplTest;
import com.cecd.sdk.rpc.RpcServer;

public class Main {
    public static void main(String[] args) {
        RpcServer rpc = new RpcServer(8090, new RpcServiceImplTest());
        rpc.start();
    }
}
