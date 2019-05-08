import com.alibaba.fastjson.JSONObject;
import com.cecd.sdk.rpc.RpcFactory;
import com.cecd.sdk.rpc.authcenter.AuthCenter;
import com.cecd.sdk.rpc.authcenter.Libraries.AccountLib;
import org.junit.Before;
import org.junit.Test;

public class RpcTest {

    @Before
    public void setUp() throws Exception {

    }

    @Test
    public void testAccountLib() {

        AuthCenter authCenter = new AuthCenter("127.0.0.1", 8090);
        AccountLib accountLib = authCenter.getRpc(AccountLib.class);
        JSONObject map = null;
        try {
            map = accountLib.check("test");
            System.out.println(map.get("data"));
            JSONObject member = (JSONObject)map.get("data");
            System.out.println(member.get("member_info"));
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("网络错误");
        }
    }
}
