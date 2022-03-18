<?php

namespace WorkWechat\Service;

use GuzzleHttp\Exception\GuzzleException;
use WorkWechat\Utils\WorkWechatException;

class DepartmentService extends BaseService
{

    const GET_DEPARTMENT_LIST = '/cgi-bin/department/list';
    const GET_DEPARTMENT_DETAIL = '/cgi-bin/department/get';

    /**
     * 获取企业部门及子部门
     * @param int $id
     * @return array
     * @throws GuzzleException
     * @throws \HttpException
     * @throws WorkWechatException
     */
    public function list(int $id = 0): array
    {
        $query = ['access_token' => $this->getAccessToken()];
        $id > 0 && $query['id'] = $id;
        $this->client->setQuery($query);
        return $this->client->get(self::GET_DEPARTMENT_LIST);

    }

    /**
     * 获取企业部门详情
     * @param int $id
     * @return array
     * @throws GuzzleException
     * @throws \HttpException
     * @throws WorkWechatException
     */
    public function detail(int $id): array
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
            'id' => $id
        ]);
        return $this->client->get(self::GET_DEPARTMENT_DETAIL);
    }


}