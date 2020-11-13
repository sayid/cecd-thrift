import com.cecd.sdk.examples.testcenter.TestCenter;
import com.cecd.sdk.examples.testcenter.TestService;
import com.cecd.sdk.examples.usercenter.UserCenter;
import com.cecd.sdk.examples.usercenter.dto.CompanyInfoDTO;
import com.cecd.sdk.examples.usercenter.entity.Member;
import com.cecd.sdk.examples.usercenter.rpcs.CompanyLibRpc;
import com.cecd.sdk.examples.usercenter.rpcs.MemberLibRpc;
import com.cecd.sdk.rpc.RpcFactory;
import com.cecd.sdk.rpc.exceptions.CeRpcException;
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


    @Test
    public void userCenter() {
        UserCenter userCenter = new UserCenter();
        CompanyLibRpc companyLibRpc = userCenter.getRpc(CompanyLibRpc.class);
        userCenter.setHost("10.32.15.112").setPort(32399).setTimeout(10000);
        //userCenter.setHost("192.168.4.5").setPort(82).setTimeout(10000);
        CompanyInfoDTO companyInfoDTO = companyLibRpc.getByCompanyId(367);
        System.out.println(companyInfoDTO.getCode());
        System.out.println(companyInfoDTO.getData().getRegisterDomain());
    }
}
