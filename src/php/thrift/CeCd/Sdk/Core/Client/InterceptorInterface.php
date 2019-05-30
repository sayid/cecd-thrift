<?php


namespace Thrift\CeCd\Sdk\Core\Client;

use Thrift\CeCd\Sdk\ResponseData;

interface InterceptorInterface
{
    /**
     * 设置一些通用的底层传输数据
     * @return array
     */
      public function before(array & $extraData);
    /*
         * {
         * $GOUUSE_MEMBER_INFO = getGouuseCore()->AuthLib->getMember();
            $GOUUSE_COMPANY_INFO = getGouuseCore()->AuthLib->getCompany();
            $member_keys = ['member_id', 'company_id', 'member_name','superior_id','name_initial','name_initial_all','role_group','sex','email','mobile','position_id','phone','department_id','status','work_number','join_time','registr_time','become_regular_time','time_delete','first_work_time', 'avatar'];
            $company_keys = ['company_id', 'company_name', 'company_short', 'company_no', 'logo', 'max_member', 'industry_id', 'admin_id'];
            $GOUUSE_MEMBER_INFO = ArrayHelper::filterArray($member_keys, $GOUUSE_MEMBER_INFO);
            $GOUUSE_COMPANY_INFO = ArrayHelper::filterArray($company_keys, $GOUUSE_COMPANY_INFO);
            return $extraData = [
                'member_id' => $GOUUSE_MEMBER_INFO['member_id'] ?? 0,
                'company_id' => $GOUUSE_MEMBER_INFO['company_id'] ?? 0,
                'member_info' => $GOUUSE_MEMBER_INFO,
                'company_info' => $GOUUSE_COMPANY_INFO,
                'from_url' => getGouuseCore()->RequestLib->fromUrl(),
                'from_service_id' => getGouuseCore()->RequestLib->getFromServiceId(),//来自哪个服务
                'form_request_id' =>  getGouuseCore()->RequestLib->getRequestId()
            ];
        }
         */

    /**
     * @param ResponseData $responseData 返回的rpc对象
     * @param double $used_time 请求时长
     * @return mixed
     */
      public function after(ResponseData $responseData,double $used_time);
        /*
        getGouuseCore()->LogLib->optimization_list[] = microtime_float() - $start . "ms rpc:" . $classpath . "->" . $name . "()";*/


}