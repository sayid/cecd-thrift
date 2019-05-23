import com.cecd.sdk.examples.usercenter.Libraries.MemberLib;
import com.cecd.sdk.examples.usercenter.Models.MemberModel;
import com.cecd.sdk.examples.usercenter.UserCenter;
import com.cecd.sdk.rpc.RpcFactory;
import org.apache.catalina.User;
import org.junit.Before;
import org.junit.Test;

public class RpcTest {

    @Before
    public void setUp() throws Exception {

    }

    @Test
    public void testUser() {
        UserCenter userCenter = RpcFactory.getInstance(UserCenter.class);
        userCenter.setHost("192.168.5.190").setPort(1005);
        MemberLib memberLib = userCenter.getRpc(MemberLib.class);
        MemberModel member = memberLib.getSimpleMemberById(1);
    }
}
