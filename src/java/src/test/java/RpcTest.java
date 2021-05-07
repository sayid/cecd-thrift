import com.xmi01.thriftrpc.examples.testcenter.TestCenter;
import com.xmi01.thriftrpc.examples.testcenter.TestService;
import com.xmi01.thriftrpc.examples.usercenter.UserCenter;
import com.xmi01.thriftrpc.examples.usercenter.entity.Member;
import com.xmi01.thriftrpc.examples.usercenter.rpcs.MemberLibRpc;
import com.xmi01.thriftrpc.rpc.RpcFactory;
import com.xmi01.thriftrpc.rpc.exceptions.CeRpcException;
import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.ObjectMapper;
import org.junit.Before;
import org.junit.Test;

import java.util.ArrayList;
import java.util.List;

public class RpcTest {

    @Before
    public void setUp() throws Exception {

    }

    @Test
    public void testUser() {
        UserCenter userCenter = RpcFactory.getInstance(UserCenter.class);
        userCenter.setHost("192.168.5.191").setPort(41005);
        System.out.println(userCenter.getLang());
        MemberLibRpc memberLib = userCenter.getRpc(MemberLibRpc.class);

        try {
            Member member = memberLib.getSimpleMemberById(100063433);
            System.out.println(member.getMemberName());
        } catch (CeRpcException e) {
            e.printStackTrace();
        }
    }

    @Test
    public void testTest() {
        TestCenter testCenter = new TestCenter();
        testCenter.setHost("127.0.0.1").setPort(8090);
        System.out.println(testCenter.getLang());
        TestService test = testCenter.getRpc(TestService.class);
        System.out.println(test.test("alibaba"));
    }

    @Test
    public void testJackson() {
        List<Object> arglist = new ArrayList<>();
        arglist.add(1);
        arglist.add("sss");
        arglist.add(1);
        try {
            ObjectMapper objectMapper = new ObjectMapper();
            System.out.println(objectMapper.writeValueAsString(arglist));
            arglist = objectMapper.readValue(objectMapper.writeValueAsString(arglist), ArrayList.class);
            arglist.add(1, "bbbbb");
            System.out.println(arglist.get(1));
        } catch (JsonProcessingException e) {
            e.printStackTrace();
        }
    }
}
