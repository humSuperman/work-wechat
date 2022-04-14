<?php

namespace WorkWechat\Service;

use WorkWechat\Utils\WorkWechatException;

class CustomerService extends BaseService
{

    const GET_CUSTOMER_LIST = '/cgi-bin/externalcontact/list';
    const GET_CUSTOMER_DETAIL = '/cgi-bin/externalcontact/get';
    const GET_CUSTOMER_BEHAVIOR_DATA = '/cgi-bin/externalcontact/get_user_behavior_data';

    /**
     * 获取员工绑定的客户列表
     * @param string $userId 企业成员的userId
     * @return array
     * @throws WorkWechatException
     */
    public function userCustomerList(string $userId): array
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
            'userid' => $userId,
        ]);
        return $this->client->get(self::GET_CUSTOMER_LIST);
    }

    /**
     * 获取客户详情
     * @param string $customerId 外部联系人的userId
     * @return array
     * @throws WorkWechatException
     */
    public function customerDetail(string $customerId): array
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
            'external_userid' => $customerId,
        ]);
        return $this->client->get(self::GET_CUSTOMER_DETAIL);
    }


    /**
     * @param array $userId
     * @param array $departmentId
     * @param int $startTime
     * @param int $endTime
     * @return array
     * @throws WorkWechatException
     */
    public function userBehaviorData(array $userId, array $departmentId, int $startTime, int $endTime): array
    {
        if(empty($userId) && $departmentId){
            throw new WorkWechatException('查询的用户和部门不能同时为空');
        }
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
        ]);
        $param = [
            "start_time" => $startTime,
            "end_time" => $endTime
        ];
        !empty($userId) && $param['userid'] = $userId;
        !empty($departmentId) && $param['partyid'] = $departmentId;
        $this->client->setJson($param);
        return $this->client->post(self::GET_CUSTOMER_BEHAVIOR_DATA);
    }
}