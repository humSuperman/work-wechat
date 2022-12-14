<?php

namespace WorkWechat\Service;

use WorkWechat\Utils\WorkWechatException;

class CustomerService extends BaseService
{

    const DAY = 86400;
    const MAX_DAY = 180 * self::DAY;
    const MAX_MONTH_DAY = 29 * self::DAY;
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
        if(empty($userId) && empty($departmentId)){
            throw new WorkWechatException('查询的用户和部门不能同时为空');
        }
        $resp = [
            'new_customer_num' => 0, // 新用户人户
            'chat_num' => 0, // 聊天总数
            'message_num' => 0, // 发消息数量
            'negative_num' => 0,//拉黑数量
        ];
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
        ]);
        !empty($userId) && $param['userid'] = $userId;
        !empty($departmentId) && $param['partyid'] = $departmentId;
        for (; $endTime >= $startTime; $endTime -= self::MAX_MONTH_DAY) {
            $s = $endTime - self::MAX_MONTH_DAY;
            $s < $startTime && $s = $startTime;
            if ($s < time() - self::MAX_DAY) {
                continue;
            }
            $param['start_time'] = $s;
            $param['end_time'] = $endTime;
            $this->client->setJson($param);
            $customerBehavior = $this->client->post(self::GET_CUSTOMER_BEHAVIOR_DATA);
            if (isset($customerBehavior['behavior_data'])) {
                foreach ($customerBehavior['behavior_data'] as $item) {
                    $resp['new_customer_num'] += $item['new_contact_cnt'];
                    $resp['chat_num'] += $item['chat_cnt'];
                    $resp['message_num'] += $item['message_cnt'];
                    $resp['negative_num'] += $item['negative_feedback_cnt'];
                }
            }

        }
        return $resp;
    }
}