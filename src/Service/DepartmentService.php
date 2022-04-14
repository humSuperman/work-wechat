<?php

namespace WorkWechat\Service;

use WorkWechat\Utils\WorkWechatException;

class DepartmentService extends BaseService
{

    const GET_DEPARTMENT_LIST = '/cgi-bin/department/list';
    const GET_DEPARTMENT_DETAIL = '/cgi-bin/department/get';
    const GET_DEPARTMENT_USER_LIST = '/cgi-bin/user/simplelist';

    /**
     * 获取企业部门及子部门
     * @param int $id
     * @return array
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

    /**
     * 获取企业部门详情
     * @param int $id
     * @param int $child
     * @return array
     * @throws WorkWechatException
     */
    public function userList(int $id, int $child = 0): array
    {
        $this->client->setQuery([
            'access_token' => $this->getAccessToken(),
            'department_id' => $id,
            'fetch_child' => $child
        ]);
        return $this->client->get(self::GET_DEPARTMENT_USER_LIST);
    }


}