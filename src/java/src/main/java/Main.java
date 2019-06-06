
import com.cecd.sdk.rpc.RpcServer;
import com.cecd.sdk.rpc.RpcServiceImpl;

public class Main {
    public static void main(String[] args) {
        RpcServer rpc = new RpcServer(8090, new RpcServiceImpl());
        rpc.start();
    }
}
