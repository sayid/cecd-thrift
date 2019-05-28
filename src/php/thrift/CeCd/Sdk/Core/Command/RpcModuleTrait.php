<?php
/**
 * Created by PhpStorm.
 * User: zhangyubo
 * Date: 2019/4/30
 * Time: 15:21
 */

namespace Thrift\CeCd\Sdk\Core\Command;

use Thrift\CeCd\Sdk\Core\Client\Rpc;
use Thrift\CeCd\Sdk\Core\Services\RpcModuleIf;

trait RpcModuleTrait
{
    private $host;

    private $port;

    private $rpcObj;

    private $lang;

    public function __construct()
    {
    
    }

    /**
     * 检查服务器，返回ok标示正常
     * @return mixed|string
     * @throws \Thrift\Exception\TException
     */
    public function ping()
    {
        $rpc = new Rpc("ping", $this);
        return $rpc->callRpc("ping", "ping", []);
    }

    abstract  protected function prepareExtra() : array;
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
     * 魔术方法自动调用rpc类
     * @param $class
     * @return Rpc
     */
    public function __get($class)
    {
        $class_load = $this->getClass($class);
        $rpc = new Rpc($class_load, $this);
        $extraData = $this->prepareExtra();
        $rpc->setExtraData($extraData);
        $this->rpcObj =  $rpc;
        return $this->rpcObj;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        if (!empty($this->host)) {
            return $this->host;
        } else {
            $host = GEnv($this->config_key.".host");
            return $host;
        }

    }

    /**
     * @return mixed
     */
    public function getPort() : int
    {
        if (!empty($this->port)) {
            return $this->port;
        } else {
            $host = GEnv($this->config_key.".port");
            return $host;
        }
    }

    public function setHost(string $host) : RpcModuleIf
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port) : RpcModuleIf
    {
        $this->port = $port;
        return $this;
    }

    public function getServiceId() : int
    {
        return $this->serviceId;
    }

    public function getLang() : string
    {
        return $this->lang;
    }

    public function getConfigKey() : string
    {
        return $this->config_key;
    }

    public function getClass($property)
    {
        $class = get_class($this);
        static $propertys = [];
        if (!isset($propertys[$property])) {
            $reflectionClass = new \ReflectionClass($class);
            $doc = $reflectionClass->getDocComment();
            $array = explode("*", $doc);
            foreach ($array as $value) {
                $value = str_replace(["\n", "\r"], "", $value);
                if (($pos = strpos($value, "@property")) !== false) {
                    $string = trim(substr($value, $pos +9,  strpos($value, "$") - $pos - 9));
                    $row = trim(substr($value, strpos($value, "$") + 1));
                    $propertys[$row] = $string;
                }
            }
        }
        if (isset($propertys[$property])) {
            return $propertys[$property];
        }
        throw new \Exception($class. "'s property is ".$property." not found");
    }

}