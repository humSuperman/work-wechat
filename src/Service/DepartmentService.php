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


    /**
     * @throws WorkWechatException
     */
    public function totalUserList(): array
    {
        $departmentList = $this->list();
        $userList = [];
        foreach ($departmentList['department'] as $department) {
            $userListWx = $this->userList($department['id']);
            $userListData = [];
            foreach ($userListWx['userlist'] as &$user) {
                $userListData[] = [
                    'department' => $department['id'],
                    'userid' => $user['userid'],
                    'name' => $user['name'],
                ];
            }
            $userList[] = [
                'id' => $department['id'],
                'name' => $department['name'],
                'parentid' => $department['parentid'],
                'userList' => $userListData,
            ];
        }
        return $userList;
    }
    public function uniqueUserList(): array
    {
        $departmentList = $this->list();
        $userList = [];
        $userIdMap = [];
        foreach ($departmentList['department'] as $department) {
            $userListWx = $this->userList($department['id']);
            foreach ($userListWx['userlist'] as &$user) {
                if(isset($userIdMap[$user['userid']])){
                    continue;
                }
                $userIdMap[$user['userid']] = 1;
                $userList[] = [
                    'department' => $department['id'],
                    'userid' => $user['userid'],
                    'name' => $user['name'],
                ];
            }
        }
        unset($userIdMap);
        return $userList;
    }

    /**
     * @throws WorkWechatException
     */
    public function departmentTreeAndTotalUser(): array
    {
        $departmentList = $this->list();
        $departmentData = [];
        $deptMap = [];
        $i = 0;
        foreach ($departmentList['department'] as $department) {
            $userListWx = $this->userList($department['id']);
            $userListData = [];
            foreach ($userListWx['userlist'] as $user) {
                $userListData[] = [
                    'id' => $user['userid'],
                    'name' => $user['name'],
                    'parentId' => $department['id'],
                    'departmentId' => $department['id'],
                ];
            }
            $departmentData[$i] = [
                'id' => $department['id'],
                'name' => $department['name'],
                'parentId' => $department['parentid'],
                'departmentId' => 0,
                'child' => $userListData,
            ];
            $deptMap[$department['id']] = &$departmentData[$i];
            $i++;
        }
        $tree= [];
        foreach ($departmentData as $idx => $department) {
            // 判断是否存在parent
            if (!isset($deptMap[$department['parentId']]) || $department['parentId'] == 0) {
                $tree[] = &$departmentData[$idx];
            } else {
                if (isset($deptMap[$department['parentId']])) {
                    $parent = &$deptMap[$department['parentId']];
                    $parent['child'][] = &$departmentData[$idx];
                }
            }
        }
        return $tree;
    }

}