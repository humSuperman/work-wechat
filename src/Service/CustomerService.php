<?php

namespace WorkWechat\Service;

use GuzzleHttp\Exception\GuzzleException;
use WorkWechat\Utils\WorkWechatException;

class CustomerService extends BaseService
{

    const GET_CUSTOMER_LIST = '/cgi-bin/externalcontact/list';
    const GET_CUSTOMER_DETAIL = '/cgi-bin/externalcontact/get';

    /**
     * 获取员工绑定的客户列表
     * @param string $userId 企业成员的userId
     * @return array
     * @throws GuzzleException
     * @throws \HttpException
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
     * @throws GuzzleException
     * @throws \HttpException
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
}