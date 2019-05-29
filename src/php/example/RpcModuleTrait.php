<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/30
 * Time: 15:21
 */

namespace Cecd\Sdk\Rpc;

use GouuseCore\Helpers\ArrayHelper;
use Thrift\CeCd\Sdk\Core\Client\Rpc;
use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;

trait RpcModuleTrait
{
    use \Thrift\CeCd\Sdk\Core\Command\RpcModuleTrait;

    /**
     * 传递自己的参数
     * @return array
     */
    protected function prepareExtra() : array
    {
        $GOUUSE_MEMBER_INFO = getGouuseCore()->AuthLib->getMember();
        $GOUUSE_COMPANY_INFO = getGouuseCore()->AuthLib->getCompany();
        $member_keys = ['member_id', 'company_id', 'member_name','superior_id','name_initial','name_initial_all','role_group','sex','email','mobile','position_id','phone','department_id','status','work_number','join_time','registr_time','become_regular_time','time_delete','first_work_time', 'avatar'];
        $company_keys = ['company_id', 'company_name', 'company_short', 'company_no', 'logo', 'max_member', 'industry_id', 'admin_id'];
        $GOUUSE_MEMBER_INFO = ArrayHelper::filterArray($member_keys, $GOUUSE_MEMBER_INFO);
        $GOUUSE_COMPANY_INFO = ArrayHelper::filterArray($company_keys, $GOUUSE_COMPANY_INFO);
        return [
            'member_id' => $GOUUSE_MEMBER_INFO['member_id'] ?? 0,
            'company_id' => $GOUUSE_MEMBER_INFO['company_id'] ?? 0,
            'member_info' => $GOUUSE_MEMBER_INFO,
            'company_info' => $GOUUSE_COMPANY_INFO,
            'from_url' => getGouuseCore()->RequestLib->fromUrl(),
            'from_service_id' => getGouuseCore()->RequestLib->getFromServiceId(),//来自哪个服务
            'form_request_id' =>  getGouuseCore()->RequestLib->getRequestId()
        ];
    }
}
