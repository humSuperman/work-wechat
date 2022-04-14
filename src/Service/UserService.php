<?php

namespace WorkWechat\Service;

use WorkWechat\Utils\WorkWechatException;

class UserService extends BaseService
{

    const GET_USER_BY_CODE = '/cgi-bin/user/getuserinfo';
    const GET_USER_DETAIL = '/cgi-bin/user/get';

    /**
     * 获取企业员工身份
     * @param string $code
     * @return array
     * @throws WorkWechatException
     */
    public function companyUserByCode(string $code): array
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
            'code' => $code
        ]);
        return $this->client->get(self::GET_USER_BY_CODE);
    }

    /**
     * 获取企业成员详情
     * @param string $userId 企业成员userId
     * @return array
     * @throws WorkWechatException
     */
    public function detail(string $userId): array
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
            'userid' => $userId
        ]);
        return $this->client->get(self::GET_USER_DETAIL);
    }

}